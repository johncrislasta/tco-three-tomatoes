<html>
<head><title><?php esc_html_e( 'Three Tomatoes Catering Report Tool.', TTC_TEXT_DOMAIN ); ?></title><style type="text/css">@page { margin: 20px; }
body { margin: 20px; }</style></head>
<body>
<h1><?php echo $title; ?></h1>
<table border="1px" cellpadding="0" cellspacing="0" style="width:100%;">
    <tr>
    	<?php foreach ($headers as $title): ?>
    	<th style="padding: 5px;"><?php echo $title; ?></th>	
    	<?php endforeach ?>        
    </tr>
    <?php foreach ($items as $item) : ?>    	
    <tr>    	

    	<td style="padding: 5px;"><?php echo $item['post_title']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['date']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['time']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['guests']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['contact']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['meal']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['order']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['event']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['venue']; ?></td>	    	
    	<td style="padding: 5px;"><?php echo $item['type']; ?></td>	    	

    </tr>
    <?php endforeach ?>        
</table>

</body>
</html>