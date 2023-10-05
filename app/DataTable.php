<?php

namespace App;

Use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DataTable
{
	public $table;

  public function __construct(string $ref = 'default', string $primary = 'id', int $limit = 10, int $offset = 0)
	{
		$this->table = [
			'ref' => $ref,
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
		];
	}

	public function setQuery(string $query, array $params = [], string $column = null, string $direction = null) {
		$this->table['query'] = str_replace('"', '', $query);

		// session()->forget($this->table['query']);

		// dd(session()->get($this->table['query']));

		if (session()->has($this->table['query'])) {
			$this->table = session()->get($this->table['query']);
			$this->table['count'] = [];
			$this->table['columns'] = [];
			$this->table['records'] = [];
			$this->table['buttons'] = [];
		}

		if ($column != null) {
			$this->table['orderColumn'] = $column;
		}

		if ($direction != null) {
			$this->table['orderDirection'] = $direction;
		}

		$query = str_replace('?', '%s', $query);
		$query = vsprintf($query, $params);

		$this->table['count'] = count(DB::select($query));

		$query = sprintf('%s ORDER BY %s %s LIMIT %s OFFSET %s', $query, $this->table['orderColumn'], $this->table['orderDirection'], $this->table['limit'], $this->table['offset']);

		$this->table['records'] = DB::select($query);
	}

	public function setTitle(string $title) {
		$this->table['title'] = $title;
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

	public function addLinkButton(string $url, string $icon, string $label = null) {
		$this->table['buttons'][] = [
			'type' => 'link',
			'icon' => $icon,
			'label' => $label,
		];

		if (!str_starts_with($url, '/')) {
			$url = '/' . $url;
		}

		if (str_contains(url()->current(), '/admin') && !preg_match('/[A-Z]/', $url)) {
			$url = '/admin' . $url;
		}

		foreach ($this->table['records'] as $i => $record) {
			$recordArray = (array) $record;
			$record->buttonRecords[] = str_replace('?', $recordArray[$this->table['primary']], $url);
		}
	}

	public function addJsButton(string $function, array $values, string $icon, string $label = null) {
		$this->table['buttons'][] = [
			'type' => 'js',
			'icon' => $icon,
			'label' => $label,
		];

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

			$record->buttonRecords[] = sprintf('%s(%s);', $function, implode(', ', $finalValues));
		}
	}

	public function calculate() {
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

		session()->put($this->table['query'], $this->table);
		session()->save();
	}

	public function render() {
		self::calculate();
		
		$html = '';

		if (!empty($this->table['title'])) {
			$html .= sprintf('
			<h3 class="table-title">%s</h3>', $this->table['title']);
		}

		$html .= sprintf('
		<table class="web-box" id="table-%s">
			<thead>
				<tr>', $this->table['ref']);

				foreach ($this->table['columns'] as $i => $column) {
					$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';
					$mobileStyle = $column['name'] == 'id' ? '50px' : $column['mobileMaxWidth'] . '%';

					if ($column['hideMobile'] == true) {
						$html .= sprintf('<th class="hide-mobile-marker" style="width:%s;">%s</th>', $style, $column['title']);
					} else {
						$html .= sprintf('<th class="show-mobile-marker" data-width="%s" data-mobile-width="%s">%s</th>', $style, $mobileStyle, $column['title']);
					}
				}

				$html .= '
				</tr>
			</thead>';

			if (count($this->table['records']) > 0) {
				$html .= '
				<tbody>';

					foreach ($this->table['records'] as $i => $record) {
						$html .= '
						<tr>';
						
						foreach ($this->table['columns'] as $i2 => $column) {
							$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';
							$mobileStyle = $column['name'] == 'id' ? '50px' : $column['mobileMaxWidth'] . '%';

							$tempResult = $record->{$column['name']};
							
							// Column Type Switch
							switch ($column['type']) {
								case 'currency':
									$tempResult = '£' . $record->{$column['name']};
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

							if ($column['hideMobile'] == true) {
								$html .= sprintf('<td id="column-%s" class="hide-mobile-marker" style="width:%s;">%s</tds>', $column['name'], $style, $tempResult);
							} else {
								$html .= sprintf('<td id="column-%s" class="show-mobile-marker" data-width="%s" data-mobile-width="%s">%s</tds>', $column['name'], $style, $mobileStyle, $tempResult);
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
												<div></div>
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
											<div></div>
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

					$html .= sprintf('
					<td>
						<i class="fa-solid fa-rectangle-list"></i> <span class="hide-mobile-marker">Records</span> <strong>%s</strong> to <strong>%s</strong>, Total: <strong>%s</strong>
					</td>', 
						$this->table['offset'] + 1, 
						$this->table['offset'] + $this->table['limit'] > $this->table['count'] ? $this->table['count'] : $this->table['offset'] + $this->table['limit'],
						$this->table['count']
					);

					$html .= sprintf('
					<td>
						<i class="fa-solid fa-layer-group"></i> Limit: <select onchange="changeLimit(event, \'%s\', \'%s\');">
							<option value="10" %s>10</option>
							<option value="25" %s>25</option>
							<option value="50" %s>50</option>
							<option value="100" %s>100</option>
							<option value="0" %s>All</option>
						</select>
					</td>',
						$this->table['query'],
						$this->table['limit'],
						$this->table['limit'] == 10 ? 'selected' : '',
						$this->table['limit'] == 25 ? 'selected' : '',
						$this->table['limit'] == 50 ? 'selected' : '',
						$this->table['limit'] == 100 ? 'selected' : '',
						$this->table['limit'] == 0 ? 'selected' : '',
					);
					
					// $html .= '
					// 	<td>
					// 		<button>button</button>
					// 	</td>';

					$html .= '
					<td>
						
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
