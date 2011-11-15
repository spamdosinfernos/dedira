<?php
$window = new GtkWindow();
$window->set_size_request(400, 280);
$window->connect_simple('destroy', array('Gtk','main_quit'));
$window->add($vbox = new GtkVBox());

// display title
$title = new GtkLabel("Display gif, jpg or png images in GtkTreeView - Part 1");
$title->modify_font(new PangoFontDescription("Times New Roman Italic 10"));
$title->modify_fg(Gtk::STATE_NORMAL, GdkColor::parse("#0000ff"));
$title->set_size_request(-1, 40);
$vbox->pack_start($title, 0, 0);
$vbox->pack_start(new GtkLabel('Red = below safety stock level (qty<10)'), 0, 0);
$vbox->pack_start(new GtkLabel('Green = above safety stock level (qty>=10)'), 0, 0);
$vbox->pack_start(new GtkLabel(), 0, 0);

// the 2D table
$data = array(
array('row0', 'item 42', 2, 3.1),
array('row1', 'item 36', 20, 6.21),
array('row2', 'item 21', 8, 9.36),
array('row3', 'item 10', 11, 12.4),
array('row4', 'item 7', 5, 15.5),
array('row5', 'item 4', 17, 18.6),
array('row6', 'item 3', 20, 21.73));

display_table ($vbox, $data);

$window->show_all();
Gtk::main();

function display_table($vbox, $data) {

	// Set up a scroll window
	$scrolled_win = new GtkScrolledWindow();
	$scrolled_win->set_policy( Gtk::POLICY_AUTOMATIC,
	Gtk::POLICY_AUTOMATIC);
	$vbox->pack_start($scrolled_win);

	// Creates the list store
	if (defined("GObject::TYPE_STRING")) {
		$model = new GtkListStore(GObject::TYPE_STRING, GObject::TYPE_STRING,
		GObject::TYPE_LONG, GObject::TYPE_DOUBLE);
	} else {
		$model = new GtkListStore(Gtk::TYPE_STRING, Gtk::TYPE_STRING,
		Gtk::TYPE_LONG, Gtk::TYPE_DOUBLE);
	}
	$field_header = array('Row #', 'Description', 'Qty', 'Price', 'Status');
	$field_justification = array(0.0, 0.0, 0.5, 1.0, .5);

	// Creates the view to display the list store
	$view = new GtkTreeView($model);
	$scrolled_win->add($view);

	// Creates the columns
	for ($col=0; $col<count($field_header); ++$col) {
		if ($field_header[$col] == 'Status') { // note 1
			$cell_renderer = new GtkCellRendererPixbuf();
			$column = new GtkTreeViewColumn();
			$column->pack_start($cell_renderer);
			$column->set_cell_data_func($cell_renderer, "format_col", $col);
		} else {
			$cell_renderer = new GtkCellRendererText();
			$cell_renderer->set_property("xalign", $field_justification[$col]);
			$column = new GtkTreeViewColumn($field_header[$col],
			$cell_renderer, 'text', $col);
			$column->set_alignment($field_justification[$col]);
			$column->set_sort_column_id($col);
		}

		// set the header font and color
		$label = new GtkLabel($field_header[$col]);
		$label->modify_font(new PangoFontDescription("Arial Bold"));
		$label->modify_fg(Gtk::STATE_NORMAL, GdkColor::parse("#0000FF"));
		$column->set_widget($label);
		$label->show();

		// setup self-defined function to display alternate row color
		$column->set_cell_data_func($cell_renderer, "format_col", $col);
		$view->append_column($column);
	}

	// pupulates the data
	for ($row=0; $row<count($data); ++$row) {
		$values = array();
		for ($col=0; $col<count($data[$row]); ++$col) {
			$values[] = $data[$row][$col];
		}
		$model->append($values);
	}

	// setup selection
	$selection = $view->get_selection();
	$selection->connect('changed', 'on_selection');
}

// self-defined function to format the price column
function format_col($column, $cell, $model, $iter, $col_num) {
	$path = $model->get_path($iter); // get the current path
	$row_num = $path[0]; // get the row number
	if ($col_num==3) {
		$amt = $model->get_value($iter, 3);
		$cell->set_property('text', '$'.number_format($amt,2));
	} elseif ($col_num==4) { // note 2
		$qty = $model->get_value($iter, 2);
		if ($qty<10) {
			$pixbuf = GdkPixbuf::new_from_file("ball_red.png");
		} else {
			$pixbuf = GdkPixbuf::new_from_file("ball_green.png");
		}
		$cell->set_property('pixbuf', $pixbuf);
	}
	$row_color = ($row_num%2==1) ? '#dddddd' : '#ffffff';
	$cell->set_property('cell-background', $row_color);
}

// process selection
function on_selection($selection) {
	list($model, $iter) = $selection->get_selected();
	$desc = $model->get_value($iter, 1);
	$qty = $model->get_value($iter, 2);
	$price = $model->get_value($iter, 3);
	print "You have selected $desc: $qty ($price)\n";
}

?>