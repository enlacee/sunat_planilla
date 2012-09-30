
jQuery(document).ready(function($) {
    jQuery('#grid').jqGrid({
        "width":"650",
        "hoverrows":false,
        "viewrecords":true,
        "jsonReader":{
            "repeatitems":false,
            "subgrid":{
                "repeatitems":false
            }
        },
    "xmlReader":{
        "repeatitems":false,
        "subgrid":{
            "repeatitems":false
        }
    },
    "gridview":true,
    "url":"grid.php",
    "editurl":"grid.php",
    "cellurl":"grid.php",
    "rowNum":10,
    "rowList":[10,20,30],
    "sortname":"EmployeeID",
    "datatype":"json",
    "colModel":[{
        "name":"EmployeeID",
        "index":"EmployeeID",
        "sorttype":"int",
        "key":true,
        "editable":false,
        "width":50,
        "label":"ID"
    },{
        "name":"TitleOfCourtesy",
        "index":"TitleOfCourtesy",
        "sorttype":"string",
        "hidden":true,
        "editable":true
    },{
        "name":"LastName",
        "index":"LastName",
        "sorttype":"string",
        "cellattr":function( rowId, value, rowObject, colModel, arrData){
            return ' colspan=2'
            },
        "formatter":function(value, options, rData){
            return rData['TitleOfCourtesy'] + ' ' + value +', '+ rData['FirstName'];
        },
        "editable":true
    },{
        "name":"FirstName",
        "index":"FirstName",
        "sorttype":"string",
        "cellattr":function( rowId, value, rowObject, colModel, arrData){
            return ' style="display:none"'
            },
        "editable":true
    },{
        "name":"BirthDate",
        "index":"BirthDate",
        "sorttype":"datetime",
        "formatter":"date",
        "formatoptions":{
            "srcformat":"Y-m-d H:i:s",
            "newformat":"m/d/Y h:i A"
        },
        "editoptions":{
            "dataInit":function(elm){
                setTimeout(function(){
                    jQuery(elm).datepicker({
                        dateFormat: 'm/d/yy',
                        timeFormat: 'hh:mm TT',
                        separator: ' ' ,
                        ampm: true
                    });
                    jQuery('.ui-datepicker').css({
                        'font-size':'75%'
                    });
                },200);
            }
        },
    "editable":true
},{
    "name":"City",
    "index":"City",
    "sorttype":"string",
    "editable":true
},{
    "name":"Region",
    "index":"Region",
    "sorttype":"string",
    "editable":true
}],
"postData":{
    "oper":"grid"
},
"prmNames":{
    "page":"page",
    "rows":"rows",
    "sort":"sidx",
    "order":"sord",
    "search":"_search",
    "nd":"nd",
    "id":"EmployeeID",
    "filter":"filters",
    "searchField":"searchField",
    "searchOper":"searchOper",
    "searchString":"searchString",
    "oper":"oper",
    "query":"grid",
    "addoper":"add",
    "editoper":"edit",
    "deloper":"del",
    "excel":"excel",
    "subgrid":"subgrid",
    "totalrows":"totalrows",
    "autocomplete":"autocmpl"
},
"loadError":function(xhr,status, err){
    try {
        jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{
            buttonalign:'right'
        });
    } catch(e) {
        alert(xhr.responseText);
    }
},
    "pager":"#pager"
});
});