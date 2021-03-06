<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Datatables Helper
 *
 * @package    CodeIgniter
 * @subpackage helpers
 * @category   helper
 * @version    1.1.4
 * @author     ZettaSys <info@zettasys.com.ar>
 *
 */
if (!function_exists('buildJS')) {

	function buildJS($tableData) {
		$CI = & get_instance();

		$columns = 'columns: [';
		$columnDefs = 'columnDefs: [';
		$columnCount = 0;
		$filters = '';
		if (isset($tableData['columns'])) {
			foreach ($tableData['columns'] as $Property) {
				$width = isset($Property['width']) ? ', "width": "' . $Property['width'] . '%"' : '';
				$className = isset($Property['class']) ? ', "className": "' . $Property['class'] . '"' : '';
				if (!isset($Property['render'])) {
					$render = '';
				} elseif ($Property['render'] === 'money') {
					$render = ', "render": function (data, type, full, meta) { if(type === "display"){if(data){data = "$ " + data.toString().replace(".", ",");}}return data;}';
				} elseif ($Property['render'] === 'date') {
					$render = ', "render": function (data, type, full, meta) { if(type === "display"){if(data){var mDate = moment(data);data = (mDate && mDate.isValid()) ? mDate.format("DD/MM/YYYY") : "";}}return data;}';
				} elseif ($Property['render'] === 'datetime') {
					$render = ', "render": function (data, type, full, meta) { if(type === "display"){if(data){var mDate = moment(data);data = (mDate && mDate.isValid()) ? mDate.format("DD/MM/YYYY HH:mm") : "";}}return data;}';
				} elseif ($Property['render'] === 'short_date') {
					$render = ', "render": function (data, type, full, meta) { if(type === "display"){if(data){var mDate = moment(data);data = (mDate && mDate.isValid()) ? mDate.format("DD/MM/YY") : "";}}return data;}';
				} elseif ($Property['render'] === 'short_datetime') {
					$render = ', "render": function (data, type, full, meta) { if(type === "display"){if(data){var mDate = moment(data);data = (mDate && mDate.isValid()) ? mDate.format("DD/MM/YY HH:mm") : "";}}return data;}';
				} else {
					$render = ', "render": ' . $Property['render'];
				}
				$visible = isset($Property['visible']) ? ', "visible": ' . $Property['visible'] : '';
				$searchable = isset($Property['searchable']) ? ', "searchable": ' . $Property['searchable'] : '';
				$sortable = isset($Property['sortable']) ? ', "sortable": ' . $Property['sortable'] : '';
				$orderData = isset($Property['orderData']) ? ', "orderData": ' . json_encode($Property['orderData']) : '';
				$columns .= '{"data": "' . $Property['data'] . '"},';
				$columnDefs .= '{'
					. '"targets": ' . $columnCount
					. $width . $className . $render . $visible . $searchable . $sortable . $orderData
					. '}, ';
				$filters .= isset($Property['filter_name']) ? '$("#' . $Property['filter_name'] . '").change(function() {var key = $(this).find(\'option:selected\').val(); var val = this.options[this.selectedIndex].text; ' . $tableData['table_id'] . '.column(' . $columnCount . ').search(key !== "Todos" ? val : "").draw(); });' . "\n" : '';
				$columnCount++;
			}
		}
		$columns .= '],';
		$columnDefs .= '],';

		$tableJS = '<script type="text/javascript">';
		$tableJS .= '$(document).ready(function() {' . "\n";
		$tableJS .= '$.fn.dataTable.moment("DD/MM/YYYY");';
		$tableJS .= (isset($tableData['reuse_var']) && $tableData['reuse_var']) ? '' : 'var ';
		$tableJS .= $tableData['table_id'] . ' = $("#' . $tableData['table_id'] . '").DataTable({';
		if (isset($tableData['paging'])) {
			$tableJS .= 'paging: ' . $tableData['paging'] . ', ';
		}
		if (isset($tableData['scrollY'])) {
			$tableJS .= 'scrollY: ' . $tableData['scrollY'] . ', ';
		}
		if (isset($tableData['scrollCollapse'])) {
			$tableJS .= 'scrollCollapse: \'' . $tableData['scrollCollapse'] . 'px\', ';
		}
		if (isset($tableData['order'])) {
			$tableJS .= 'order: ' . json_encode($tableData['order']) . ', ';
		}
		if (isset($tableData['fnHeaderCallback'])) {
			$tableJS .= 'fnHeaderCallback: ' . str_replace('"', '', json_encode($tableData['fnHeaderCallback'], JSON_UNESCAPED_SLASHES)) . ',';
		}
		if (isset($tableData['fnRowCallback'])) {
			$tableJS .= 'fnRowCallback: ' . str_replace('"', '', json_encode($tableData['fnRowCallback'], JSON_UNESCAPED_SLASHES)) . ',';
		}
		if (isset($tableData['initComplete'])) {
			$tableJS .= 'initComplete: ' . str_replace('"', '', json_encode($tableData['initComplete'], JSON_UNESCAPED_SLASHES)) . ',';
		}
		if (isset($tableData['lengthMenu'])) {
			$tableJS .= 'lengthMenu: ' . json_encode($tableData['lengthMenu']) . ',';
		}
		if (isset($tableData['rowReorder'])) {
			$tableJS .= 'rowReorder: ' . json_encode($tableData['rowReorder']) . ',';
		}
		if (isset($tableData['disableLengthChange']) && $tableData['disableLengthChange']) {
			$tableJS .= 'lengthChange: false,';
		}
		if (isset($tableData['disableSearching']) && $tableData['disableSearching']) {
			$tableJS .= 'searching: false,';
		}
		if (isset($tableData['disablePagination']) && $tableData['disablePagination']) {
			$tableJS .= 'bPaginate: false, ';
		}
		if (isset($tableData['dom'])) {
			$tableJS .= 'dom: \'' . $tableData['dom'] . '\', ';
		}
		if (isset($tableData['details_format'])) {
			$tableJS .= 'responsive: { details: { renderer: ' . $tableData['details_format'] . '}}, ';
//			$tableJS .= 'responsive: { details: { display: $.fn.dataTable.Responsive.display.childRowImmediate, renderer: ' . $tableData['details_format'] . '}}, ';
//			$tableJS .= 'responsive: { details: { display: $.fn.dataTable.Responsive.display.modal(), renderer: ' . $tableData['details_format'] . '}}, ';
		}
		if (isset($tableData['pagingType'])) {
			$tableJS .= 'pagingType: "' . $tableData['pagingType'] . '", ';
		} else {
			$tableJS .= 'pagingType: "simple_numbers", ';
		}
		$tableJS .= 'processing: true, ';
		if (!isset($tableData['saveState']) || $tableData['saveState']) {
			$tableJS .= 'stateSave: true, ';
		}
		$tableJS .= 'serverSide: true, '
			. 'autoWidth: false, '
			. 'language: {"url": "plugins/datatables/spanish.json"}, ';

		if (isset($tableData['extraData'])) {
			$data = 'data: function (d) {' . $tableData['extraData'] . 'd.' . $CI->security->get_csrf_token_name() . '= "' . $CI->security->get_csrf_hash() . '";}';
		} else {
			$data = 'data: {' . $CI->security->get_csrf_token_name() . ':"' . $CI->security->get_csrf_hash() . '"}';
		}

		$tableJS .= 'ajax: {'
			. 'url: "' . $tableData['source_url'] . '", '
			. 'type: "POST", '
			. $data . '}, ';

		$tableJS .= $columns;
		$tableJS .= $columnDefs;
		$tableJS .= 'colReorder: true';
		$tableJS .= '});' . "\n";
		$tableJS .= $filters;
		$tableJS .= '});';
		$tableJS .= '</script>';
		return $tableJS;
	}
}

if (!function_exists('buildHTML')) {

	function buildHTML($tableData) {
		if (isset($tableData['responsive']) && !$tableData['responsive']) {
			$classes = 'table table-hover table-bordered table-condensed';
		} else {
			$classes = 'table table-hover table-bordered table-condensed dt-responsive';
		}
		$tableHTML = '<table id="' . $tableData['table_id'] . '" class="' . $classes . '">'; // nowrap
		$tableHTML .= '<thead>';
		$tableHTML .= '<tr>';
		if (isset($tableData['columns'])) {
			foreach ($tableData['columns'] as $Column) {
				$class = empty($Column['responsive_class']) ? '' : ' class="' . $Column['responsive_class'] . '"';
				$priority = empty($Column['priority']) ? '' : ' data-priority="' . $Column['priority'] . '"';
				$tableHTML .= "<th$class$priority>";
				$tableHTML .= $Column['label'];
				$tableHTML .= "</th>";
			}
		}
		$tableHTML .= '</tr>';
		$tableHTML .= '</thead>';
		if (isset($tableData['footer']) && $tableData['footer']) {
			$tableHTML .= '<tfoot>';
			$tableHTML .= '<tr>';
			if (isset($tableData['columns'])) {
				foreach ($tableData['columns'] as $Column) {
					$tableHTML .= "<th>";
					$tableHTML .= "</th>";
				}
			}
			$tableHTML .= '</tr>';
			$tableHTML .= '</tfoot>';
		}
		$tableHTML .= '</table>';

		return $tableHTML;
	}
}