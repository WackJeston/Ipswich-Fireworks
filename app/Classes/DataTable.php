<?php

namespace App\Classes;

Use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Classes\ImageCommon;

class DataTable
{
	public $table;

  public function __construct(string $ref = 'default', string $primary = 'id', int $limit = 10, int $offset = 0)
	{
		$this->table = [
			'ref' => $ref,
			'sessionVariable' => trim(str_replace('/', '-', $_SERVER['REQUEST_URI'] . 'REF-' . $ref), '-'),
			// 'sessionVariable' => urlencode($_SERVER['REQUEST_URI'] . 'REF-' . $ref),
			'tableName' => explode('_REF_', $ref)[0],
			'primary' => $primary,
			'limit' => $limit,
			'offset' => $offset,
			'orderColumn' => 'id',
			'orderDirection' => 'ASC',
			'count' => 0,
			'columns' => [],
			'records' => [],
			'buttons' => [],
			'sequence' => false,
			'sequenceColumn' => 'null',
			'highlight' => null,
		];
	}

	public function setQuery(string $query, array $params = [], string $column = null, string $direction = null) {
		$query = json_encode($query);
		$query = str_replace('\t', '', $query);
		$query = str_replace('\r', ' ', $query);
		$query = str_replace('\n', ' ', $query);
		$query = str_replace('   ', ' ', $query);
		$query = str_replace('  ', ' ', $query);
		$query = json_decode($query);
		$query = trim($query);
		// $query = str_replace('"', '&quot;', (string) $query);

		$query = str_replace('?', '%s', $query);
		$query = vsprintf($query, $params);

		$this->table['query'] = $query;

		// dd(session()->all());

		if (session()->has($this->table['sessionVariable'])) {
			$this->table = session()->get($this->table['sessionVariable']);
			$this->table['count'] = [];
			$this->table['columns'] = [];
			$this->table['records'] = [];
			$this->table['buttons'] = [];
		
		} else {
			if ($column != null) {
				$this->table['orderColumn'] = $column;
			}
	
			if ($direction != null) {
				$this->table['orderDirection'] = $direction;
			}
		}

		$this->table['count'] = count(DB::select($query));

		if ($this->table['sequence'] == true && $this->table['count'] < 2) {
			$this->table['sequence'] = false;
		}

		if ($this->table['limit'] == 0 && $this->table['offset'] != 0) {
			$this->table['offset'] = 0;
		}

		$orderColumnBackticks = explode(', ', $this->table['orderColumn']);

		foreach ($orderColumnBackticks as $i => $column) {
			$orderColumnBackticks[$i] = sprintf("`%s`", $column);
		}

		$orderColumnBackticks = implode(', ', $orderColumnBackticks);

		$query = sprintf('%s ORDER BY %s %s%s%s', 
			$query, 
			$orderColumnBackticks, 
			$this->table['orderDirection'], 
			$this->table['limit'] == 0 ? ' LIMIT 500' : ' LIMIT ' . $this->table['limit'],
			$this->table['offset'] == 0 ? '' : ' OFFSET ' . $this->table['offset']
		);

		$this->table['records'] = DB::select($query);

		$this->table['records'] = ImageCommon::cacheImages($this->table['records']);
	}

	public function setTitle(string $title) {
		$this->table['title'] = $title;
	}

	public function sequence(string $sequenceColumn = 'null') {
		$this->table['sequence'] = true;
		$this->table['sequenceColumn'] = $sequenceColumn;
		$this->table['orderColumn'] = 'sequence';
	}

	public function addColumn(string $name, string $title = null, int $width = 1, bool $hideMobile = false, string $type = 'default', array $typeData = []) {
		if ($title == null) {
			$title = $name;
		}

		$typeArray = explode(':', $type);

		$type = $typeArray[0];
		$parent = isset($typeArray[1]) ? $typeArray[1] : null;
		$parentId = isset($typeArray[2]) ? $typeArray[2] : null;

		$this->table['columns'][] = [
			'name' => $name,
			'title' => $title,
			'width' => $width,
			'maxWidth' => $width,
			'mobileMaxWidth' => $width,
			'hideMobile' => $hideMobile,
			'type' => $type,
			'typeData' => $typeData,
			'parent' => $parent,
			'parentId' => $parentId,
		];
	}

	public function addLinkButton(string $url, string $icon, string $label = null, bool $internal = true) {
		$this->table['buttons'][] = [
			'type' => 'link',
			'icon' => $icon,
			'label' => $label,
		];

		if ($internal) {
			if (!str_starts_with($url, '/')) {
				$url = '/' . $url;
			}
	
			if (str_contains(url()->current(), '/admin') && !preg_match('/[A-Z]/', $url)) {
				$url = '/admin' . $url;
			}
		}

		foreach ($this->table['records'] as $i => $record) {
			$recordArray = (array) $record;
			$record->buttonRecords[] = str_replace('?', $recordArray[$this->table['primary']], $url);
		}
	}

	public function addJsButton(string $function, array $values, string $icon, string $label = null, bool $addToStart = false) {
		if (!$addToStart) {
			$this->table['buttons'][] = [
				'type' => 'js',
				'icon' => $icon,
				'label' => $label,
			];

		} else {
			array_unshift($this->table['buttons'], [
				'type' => 'js',
				'icon' => $icon,
				'label' => $label,
			]);
		}

		foreach ($this->table['records'] as $i => $record) {
			$finalValues = [];

			foreach ($values as $i2 => $value) {
				$value = explode(':', $value);

				$tempValue = '';
				
				switch ($value[0]) {
					case 'string':
						$tempValue = $value[1];
						break;
					
					case 'record':
						$tempValue = $record->{$value[1]};
						break;
					
					case 'url':
						$tempValue = str_replace('?', $record->{$this->table['primary']}, $value[1]);
						break;
				}
	
				$finalValues[] = "'$tempValue'";
			}

			if (!$addToStart) {
				$record->buttonRecords[] = sprintf('%s(%s);', $function, implode(', ', $finalValues));
				
			} else {
				array_unshift($record->buttonRecords, sprintf('%s(%s);', $function, implode(', ', $finalValues)));
			}
		}
	}

	public function highlight(string $column, $value, bool $true = true) {
		foreach ($this->table['records'] as $i => $record) {
			if ($true) {
				if ($record->{$column} == $value) {
					$this->table['records'][$i]->highlight = true;
				}
			} else {
				if ($record->{$column} != $value) {
					$this->table['records'][$i]->highlight = true;
				}
			}
		}
	}

	private function calculate() {
		if ($this->table['sequence'] == true && $this->table['orderColumn'] == 'sequence') {
			Self::addJsButton('moveSequence', ['record:id', 'string:up', sprintf('string:%s', $this->table['ref']), sprintf('string:%s', $this->table['tableName']), sprintf('string:%s', $this->table['sequenceColumn'])], 'fa-solid fa-angle-up', 'Move Up', true);
			Self::addJsButton('moveSequence', ['record:id', 'string:down', sprintf('string:%s', $this->table['ref']), sprintf('string:%s', $this->table['tableName']), sprintf('string:%s', $this->table['sequenceColumn'])], 'fa-solid fa-angle-down', 'Move Down', true);
		}

		$columnWidthCount = 0;
		$mobileColumnWidthCount = 0;
		
		foreach ($this->table['columns'] as $i => $column) {
			if ($column['name'] != 'id') {
				$columnWidthCount += $column['width'];

				if ($column['hideMobile'] == false) {
					$mobileColumnWidthCount += $column['width'];
				}
			}
		}

		foreach ($this->table['columns'] as $i => $column) {
			if ($column['name'] != 'id') {
				$this->table['columns'][$i]['maxWidth'] = round((100 / $columnWidthCount) * $column['width'], 2);

				if ($column['hideMobile'] == false) {
					$this->table['columns'][$i]['mobileMaxWidth'] = round((100 / $mobileColumnWidthCount) * $column['width'], 2);
				};
			}
		}

		if (!empty($this->table['title'])) {
			$this->table['title'] = str_replace('?', $this->table['count'], $this->table['title']);
		}

		session()->put($this->table['sessionVariable'], $this->table);

		$this->table['query'] = str_replace('"', '&quot;', $this->table['query']);
	}

	public function render() {
		self::calculate();

		$html = '';

		if (!empty($this->table['title'])) {
			$html .= sprintf('
			<h3 class="table-title">%s</h3>', $this->table['title']);
		}

		$html .= sprintf('
		<table class="data-table web-box dk" id="table-%s">
			<thead>
				<tr>', $this->table['ref']);

				foreach ($this->table['columns'] as $i => $column) {
					$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';
					$mobileStyle = $column['name'] == 'id' ? '50px' : $column['mobileMaxWidth'] . '%';

					$click = sprintf('onclick="setOrderDirection(\'%1$s\', \'%2$s\', \'%3$s\')"',
						$this->table['orderDirection'] == 'DESC' ? 'ASC' : 'DESC', 
						$this->table['sessionVariable'], 
						$this->table['ref']
					);

					$orderDirection = $this->table['orderDirection'] == 'DESC' ? sprintf(' <i class="fa-solid fa-angle-up" %s></i>', $click) : sprintf(' <i class="fa-solid fa-angle-down" %s></i>', $click);
					$orderColumn = $this->table['orderColumn'] == $column['name'] ? $orderDirection : '';

					$click = sprintf('onclick="setOrderColumn(event, \'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')"',
						$column['name'],
						$this->table['orderColumn'], 
						$this->table['sessionVariable'], 
						$this->table['ref']
					);

					if ($column['name'] == 'id') {
						$html .= sprintf('<th id="column-%1$s" class="column-%1$s" style="width:%2$s;" %5$s><span>%3$s%4$s</span></th>',
							$column['name'],
							$style,
							$column['title'],
							$orderColumn,
							$click
						);
					
					} else {
						if ($column['hideMobile'] == true) {
							$html .= sprintf('<th class="hide-mobile-marker" style="width:%s;" %s>%s%s</th>', $style, $click, $column['title'], $orderColumn);
						} else {
							$html .= sprintf('<th class="show-mobile-marker" data-width="%s" data-mobile-width="%s" %s>%s%s</th>', $style, $mobileStyle, $click, $column['title'], $orderColumn);
						}
					}
				}

				$html .= '
				</tr>
			</thead>';

			if (count($this->table['records']) > 0) {
				$html .= '
				<tbody>';

					foreach ($this->table['records'] as $i => $record) {
						$highlight = (isset($record->highlight) && $record->highlight) ? 'highlight' : '';

						$html .= sprintf('
						<tr id="row-index-%d" data-record-id="%d" class="%s">', $i, $record->{$this->table['primary']}, $highlight);
						
						foreach ($this->table['columns'] as $i2 => $column) {
							$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';
							$mobileStyle = $column['name'] == 'id' ? '50px' : $column['mobileMaxWidth'] . '%';
							$paragraph = false;

							$tempResult = $record->{$column['name']};
							
							// Column Type Switch
							switch ($column['type']) {
								case 'currency':
									$tempResult = '£' . $record->{$column['name']};
									break;

								case 'paragraph':
									$paragraph = true;
									break;

								case 'toggle':
									if ($record->{$column['name']} == 1) {
										$tempResult = sprintf('<i class="fa-solid fa-circle-check toggle-true" id="%3$s-%5$s" onclick="toggleButton(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']}
										);
									} else {
										$tempResult = sprintf('<i class="fa-solid fa-circle-xmark toggle-false" id="%3$s-%5$s" onclick="toggleButton(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']}
										);
									}
									break;
								case 'setPrimary':
									if ($record->{$column['name']} == 1) {
										$tempResult = sprintf('<i class="fa-solid fa-circle-check toggle-true" id="%3$s-%5$s" onclick="setPrimary(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\', \'%6$s\', \'%7$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']},
											$column['parent'],
											$column['parentId'],
										);
									} else {
										$tempResult = sprintf('<i class="fa-solid fa-circle-xmark toggle-false" id="%3$s-%5$s" onclick="setPrimary(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\', \'%6$s\', \'%7$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']},
											$column['parent'],
											$column['parentId'],
										);
									}
									break;
								case 'select':
									$tempResult = sprintf('<select onclick="selectDropdown(event, \'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')">',
										$this->table['tableName'],
										$column['name'], 
										$this->table['primary'], 
										$record->{$this->table['primary']},
									);

									foreach ($column['typeData'] as $i3 => $option) {
										$selected = $option['value'] == $record->{$column['name']} ? 'selected' : '';

										$tempResult .= sprintf('<option value="%1$s" %3$s>%2$s</option>', 
											$option['value'], 
											$option['label'],
											$selected,
										);
									}
									$tempResult .= '</select>';

									break;
							}

							if ($column['name'] == 'id') {
								$html .= sprintf('<td id="column-%1$s" class="column-%1$s" style="width:%2$s;"><span>%3$s<span></td>', $column['name'], $style, $tempResult);
							
							} else {
								if ($column['hideMobile'] == true) {
									$html .= sprintf('<td id="column-%1$s" class="column-%1$s%4$s hide-mobile-marker" style="width:%2$s;">%3$s</td>', $column['name'], $style, $tempResult, $paragraph ? ' paragraph' : '');
								} else {
									$html .= sprintf('<td id="column-%1$s" class="column-%1$s%5$s show-mobile-marker" data-width="%2$s" data-mobile-width="%3$s">%4$s</td>', $column['name'], $style, $mobileStyle, $tempResult, $paragraph ? ' paragraph' : '');
								}
							}
						}
	
						if (count($this->table['buttons']) >= 1) {
							$html .= '
							<td class="tr-buttons">';
	
							foreach ($this->table['buttons'] as $i3 => $button) {
								if ($button['type'] == 'link') {
									$html .= sprintf('
									<a href="%s">
										<i class="%s tr-button">', $record->buttonRecords[$i3], $button['icon']);
		
										if ($button['label'] != null) {
											$html .= sprintf('
											<div class="button-label">
												<p>%s</p>
											</div>', $button['label']);
										}
										
										$html .= '
										</i>
									</a>';

								} elseif ($button['type'] == 'js') {
									$html .= sprintf('
									<i onclick="%s" class="%s tr-button">', $record->buttonRecords[$i3], $button['icon']);
		
									if ($button['label'] != null) {
										$html .= sprintf('
										<div class="button-label">
											<p>%s</p>
										</div>', $button['label']);
									}
									
									$html .= '
									</i>';
								}
							}
	
							$html .= '
							</td>';
						}
	
						$html .= '
						</tr>';
					}

				$html .= '
				</tbody>';

			} else {
				$html .= '
				<tr class="empty-table">
					<td><h3>No Records</h3></td>
				</tr>';
			}

			$html .= '
			<tfoot>
				<tr>';

					if (($this->table['offset'] + $this->table['limit']) >= $this->table['count'] || $this->table['limit'] == 0) {
						$offset = $this->table['count'];

					} else {
						$offset = $this->table['offset'] + $this->table['limit'];
					}

					$html .= sprintf('
					<td>
						<i class="fa-solid fa-rectangle-list hide-mobile-xs-marker"></i> <span class="hide-mobile-marker">Records</span> <strong>%s</strong> to <strong>%s</strong>, Total: <strong>%s</strong>
					</td>', 
						$this->table['offset'] + 1, 
						$offset,
						$this->table['count']
					);

					$html .= sprintf('
					<td>
						<i class="fa-solid fa-layer-group hide-mobile-xs-marker"></i> Limit: <select onchange="changeTableLimit(event, \'%s\', \'%s\', \'%s\');">
							<option value="10" %s>10</option>
							<option value="25" %s>25</option>
							<option value="50" %s>50</option>
							<option value="100" %s>100</option>
							<option value="0" %s>All</option>
						</select>
					</td>',
						$this->table['sessionVariable'],
						$this->table['limit'],
						$this->table['ref'],
						$this->table['limit'] == 10 ? 'selected' : '',
						$this->table['limit'] == 25 ? 'selected' : '',
						$this->table['limit'] == 50 ? 'selected' : '',
						$this->table['limit'] == 100 ? 'selected' : '',
						$this->table['limit'] == 0 ? 'selected' : '',
					);
					
					if ($this->table['sequence'] == true && $this->table['orderColumn'] != 'sequence') {
						$html .= '
						<td>';

							$html .= sprintf('
								<div class="icon-container" onclick="resetTableSequence(\'%s\', \'%s\')">
									<i class="fa-solid fa-arrow-down-short-wide hide-mobile-xs-marker"></i> Reset <span class="hide-mobile-marker">Sequence</span>
								</div>',	
								$this->table['sessionVariable'],
								$this->table['ref']
							);

						$html .= '
						</td>';
					}

					$html .= '
					<td>';
					
						if ($this->table['offset'] > 0 && $this->table['limit'] != 0) {
							$html .= sprintf('
							<button class="lt" onclick="changeTablePage(\'%1$s\', \'%2$d\', \'%3$d\', false, \'%4$s\')"><i class="fa-solid fa-caret-left"></i></button>',
								$this->table['sessionVariable'],
								$this->table['offset'],
								$this->table['limit'],
								$this->table['ref']
							);
						
						} else {
							$html .= '<button class="button-off"><i class="fa-solid fa-caret-left"></i></button>';
						}

						if ($this->table['count'] > ($this->table['offset'] + $this->table['limit']) && $this->table['limit'] != 0) {
							$html .= sprintf('
							<button class="lt" onclick="changeTablePage(\'%1$s\', \'%2$d\', \'%3$d\', true, \'%4$s\')"><i class="fa-solid fa-caret-right"></i></button>',
								$this->table['sessionVariable'],
								$this->table['offset'],
								$this->table['limit'],
								$this->table['ref']
							);
						
						} else {
							$html .= '<button class="button-off"><i class="fa-solid fa-caret-right"></i></button>';
						}

					$html .= '
					</td>';

				$html .= '
				</tr>
			</tfoot>';
				
		$html .= '
		</table>';

		$return = [
			'html' => trim(preg_replace('/\s\s+/', '', $html)),
			'count' => $this->table['count'],
		];

		return $return;
	}
}
