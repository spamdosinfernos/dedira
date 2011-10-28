<?php
$window = new GtkWindow();
$window->set_title($argv[0]);
$window->set_size_request(400, 250);
$window->connect_simple('destroy', array('Gtk','main_quit'));
$window->add($vbox = new GtkVBox());

$html = new GtkHTML(); // note 1

$vbox->pack_start($html);

$html_text = "<h1>GtkHTML Demo</h1>
<p><i>hello <font color=blue>world</font></i>, <b>gtkhtml!</b></p>

<table border=4>
<tr><th>A</th><th>B</th></tr>
<tr><td>a1.1</td><td bgcolor=#BAFFBF>b1.2</td></tr>
<tr><td bgcolor=#BAFFBF>a2.1</td><td>b2.2</td></tr>
</table>

<ul>
<li>item 1</li>
<li>item 2</li>
<li>item 3</li>
</ul>
";

$html->load_from_string($html_text); // note 2

$window->show_all();
Gtk::main();
?>