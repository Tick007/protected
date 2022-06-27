 <script>
  var marked_row = new Array;
function markAllRows( container_id ) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];

        if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            if ( checkbox.disabled == false ) {
                checkbox.checked = true;
                if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
                    rows[i].className += ' marked';
                    marked_row[unique_id] = true;
                }
            }
        }
    }

    return true;
}

function unMarkAllRows( container_id ) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];

        if ( checkbox && checkbox.type == 'checkbox' ) {
            //unique_id = checkbox.name + checkbox.value;
			unique_id = checkbox.name;
            checkbox.checked = false;
            rows[i].className = rows[i].className.replace(' marked', '');
            marked_row[unique_id] = false;
        }
    }

    return true;
}

function PMA_markRowsInit() {
    // for every table row ...
    var rows = document.getElementsByTagName('tr');
    for ( var i = 0; i < rows.length; i++ ) {
        // ... with the class 'odd' or 'even' ...
        if ( 'odd' != rows[i].className.substr(0,3) && 'even' != rows[i].className.substr(0,4) ) {
            continue;
        }
        // ... add event listeners ...
        // ... to highlight the row on mouseover ...
        if ( navigator.appName == 'Microsoft Internet Explorer' ) {
            // but only for IE, other browsers are handled by :hover in css
            rows[i].onmouseover = function() {
                this.className += ' hover';
            }
            rows[i].onmouseout = function() {
                this.className = this.className.replace( ' hover', '' );
            }
        }
        // Do not set click events if not wanted
        if (rows[i].className.search(/noclick/) != -1) {
            continue;
        }
        // ... and to mark the row on click ...
        rows[i].onmousedown = function() {
            var unique_id;
            var checkbox;

            checkbox = this.getElementsByTagName( 'input' )[0];
            if ( checkbox && checkbox.type == 'checkbox' ) {
                unique_id = checkbox.name + checkbox.value;
            } else if ( this.id.length > 0 ) {
                unique_id = this.id;
            } else {
                return;
            }

            if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
                marked_row[unique_id] = true;
            } else {
                marked_row[unique_id] = false;
            }

            if ( marked_row[unique_id] ) {
                this.className += ' marked';
            } else {
                this.className = this.className.replace(' marked', '');
            }

            if ( checkbox && checkbox.disabled == false ) {
                checkbox.checked = marked_row[unique_id];
            }
        }

        // ... and disable label ...
        var labeltag = rows[i].getElementsByTagName('label')[0];
        if ( labeltag ) {
            labeltag.onclick = function() {
                return false;
            }
        }
        // .. and checkbox clicks
        var checkbox = rows[i].getElementsByTagName('input')[0];
        if ( checkbox ) {
            checkbox.onclick = function() {
                // opera does not recognize return false;
                this.checked = ! this.checked;
            }
        }
    }
}



window.onload=PMA_markRowsInit;
</script>

<?
echo $new->contents.'<br>';
echo CHtml::beginForm(array('/adminpages/sendnews/', 'id'=>$new->id), 'POST',$htmlOptions=array ('id'=>'form1'));

?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" style="float:left; text-align:left; font-family:Microsoft Sans Serif; font-size:x-small">
 
    <td>&nbsp;</td>
    <td align="right"><a href="#"  onclick="if (markAllRows('form1')) return false;" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:x-small">выдел. все</a>/<a href="#" onClick="if (unMarkAllRows('form1')) return false;" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:x-small">снять выдел.</a></td>
  </tr>

<?
for ($i=0; $i<count($models); $i++) {
echo CHtml::hiddenField('emailtosend['.$models[$i]->id.']', $models[$i]->client_email);
?>
 <tr>
<td>
<?
echo $models[$i]->first_name.', '.$models[$i]->client_email;
?></td>
    <td align="right">
<?
echo CHtml::checkBox('send['.$models[$i]->id.']');
?>
</td>
</tr>
<?
}////////////for ($i=0; $i<count($models); $i++) {
?>
</table>
<?
 echo CHtml::submitButton('Разослать', $htmlOptions=array ('name'=>'sendsubscrs' ));
echo CHtml::endForm(); 
?>