<HTML>
<HEAD>
<TITLE>Widget Test Page</TITLE>
</HEAD>
<BODY>
<H1>Load widget</H1>
The widget is loaded as follows:
<ol>
	<li>name a script tag: sciomino_widget</li>
	<li>load widget loader: /js/sciomino/widget.load-min.js</li>
	<li>add parameters to widget loader:</li>
<ul>
	<li><b>wid:</b>your unique widget id (defines the network AND widgettype: 'act' or 'user')</li>
	<li><b>width:</b>the desired width of the widget</li>
</ul>

	<br/>
	<li>additional parameters to focus 'act' &amp; 'user' list:</li>
<ul>
	<li><b>k[NAME]:</b>to specify a knowledge field, for example: k[Php]</li>
	<li><b>h[NAME]:</b>to specify a hobby, for example: h[Yoga]</li>
</ul>
	<li>more additional parameters to focus 'user' list only:</li>
<ul>
	<li><b>k[NAME]=level:</b>to specify a knowledge field with level=1-3, for example: k[Php]=1</li>
	<li><b>t[NAME]:</b>to specify a tag, for example: t[#sciomino]</li>
	<li><b>p[NAME]=VALUE:</b>to specify a personal name/value pair, for example: p[role]=manager</li>
	<li><b>e[CATEGORY][NAME]:</b>to specify an experience within a category, for example: e[Product][auto]</li>
	<li><b>e[CATEGORY][NAME]=title,alternative,like,has:</b>to specify an experience within a category with a specific title and alternative (and like and has), for example: e[Product][auto]=rover,25,,</li>
</ul>

	<br/>
	<li>and...automagically we create a container 'sciomino_widget_container' that contains the content of the widget</li>
<ul>
	<li>for now the widget is defined by sciomino based on wid</li>
	<li>later the widget can be modified online by the customer (a la twitter)</li>
</ul>
</ol>

<h2>The client code (network:CLICKNL and widgettype:ACT and language:NL)</h2>

<pre>
&lt;script id="sciomino_widget" src="http://sciomino-www-1.2/js/sciomino/widget.load-min.js?wid=1a26785542c65efe755ff4809f9b16bf&amp;width=250" type="text/javascript" async&gt;&lt;/script&gt;
</pre>

<h2>The result</h2>

<script id="sciomino_widget" src="http://sciomino-www-1.2/js/sciomino/widget.load-min.js?wid=1a26785542c65efe755ff4809f9b16bf&width=250" type="text/javascript" async></script>

</BODY>
</HTML>
