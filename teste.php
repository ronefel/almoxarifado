<?php
echo $_SERVER['SCRIPT_NAME'];
echo '<br/>';
echo $_SERVER['SCRIPT_FILENAME'];

echo '<br/>';
echo str_replace("/farol.edu.br", "", $_SERVER['SCRIPT_FILENAME']);
?>

<script>
    $(function() {
        $.ajax({
            type: "POST",
            url: "/",
            dataType: "html",
            data: {"control": "view"},
            cache: false,
            success: function(html) {
                $("#index-body").html(html);
            }
        });
    });
</script>
<div id="index-body">
    
</div> 