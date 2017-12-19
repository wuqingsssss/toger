<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <button id="save-btn" data-loading-text="loading..." class="btn btn-primary">
                <?php echo $button_save; ?>
            </button>
            <input type="button" onclick="history.go(-1)" class="btn btn-default"
                   value="<?php echo $button_cancel; ?>"> 
        </div>
    </div>
    <div class="content">
        <div id="tabs" class="vtabs">
            <a href="#tab-general" class="first"><?php echo $tab_general; ?></a>
            <?php foreach($products as $dkey=> $ddata){?>
            <a href="#tab-products-<?php echo $dkey;?>"><?php echo $ddata['region_name'].'['.$deliverys[$ddata['delivery_code']]['title'].']';?></a>
            <?php }?>
            <a id='addmodule' href="#tab-newsite">添加新站点</a>
        </div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"
              class="form-horizontal">
            <input type="hidden" name="productIds"/>

            <div id="tab-general" class="vtabs-content">
                <table class="form">
                	<tr>
                        <td><span class="required">*</span>T+0周期名称:</td>
                        <td><input  type="text" name="title"
                                   value="<?php echo $result['title']; ?>"
                                   required=""/></td>
                    </tr>
                	<tr>
                        <td><span class="required">*</span>档期名称:</td>
                        <td><input  type="text" name="name"
                                   value="<?php echo $result['name']; ?>"
                                   required=""/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span>开始时间:</td>
                        <td><input class="date" type="text" name="start_date"
                                   value="<?php echo(isset($result['start_date']) ? date('Y-m-d', strtotime($result['start_date'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span>结束时间:</td>
                        <td><input class="date" type="text" name="end_date"
                                   value="<?php echo(isset($result['end_date']) ? date('Y-m-d', strtotime($result['end_date'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                     <tr>
                        <td><span class="required"></span>排序:</td>
                        <td><input  type="text" name="sort_order"
                                   value="<?php echo $result['sort_order']; ?>" /></td>
                    </tr>
                    
                    <tr>
                        <td><span class="required"></span>展示模板:</td>
                        <td><input  type="text" name="template"
                                   value="<?php echo $result['template']; ?>" />（*不填写表示使用系统模板）</td>
                    </tr>
                    <tr>
                        <td>
                            周期简介
                        </td>
                        <td>
                            <textarea name="info" id="description">
                                <?php echo $result['info']; ?>
                            </textarea>
                        </td>
                    </tr>
                </table>
            </div>
             <?php
              $starttime = strtotime($result['start_date']); 
              $endtime = strtotime($result['end_date']); 
              foreach($products as $dkey=> $ddata){?>
            <div id="tab-products-<?php echo $dkey;?>" class="vtabs-content">
                <span class="help"><?php echo $text_period_help;?></span><br/>
                <table class="list" id="products">
                    <thead>
                    <tr><td class="right" width=100px>顺序</td>
                        <td class="left">商品</td>
                        <td class="left">型号</td>
                        <td class="right">价格</td>
                        <?php 
$inum=0;
                        for ($i=$starttime;$i<=$endtime; $i= $i + 86400){
$inum++;
                        ?>
                        <td class="right"><?php echo date('m/d',$i);?></td>
                        <?php }?>
                        <td class="right">管理</td>
                    </tr>
                    </thead>
                    <tbody id="main-tbody-<?php echo $dkey;?>">
                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="1">
                            <input type="text" placeholder="插入位置" style="margin: 5px 0;width: 100px;" id="insert-pos-<?php echo $dkey;?>"/>
                        </td>
                        <td colspan="<?php echo $inum+4;?>">
                            <input type="text" placeholder="输入名称" class="insert-field" data-key="<?php echo $dkey;?>" style="margin: 5px 0;width: 200px;" id="insert-field-<?php echo $dkey;?>"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <?php }?>
            <div id="tab-newsite" class="vtabs-content box">
                <span class="help">单击以添加新站点到周期</span>
                <?php foreach($deliverys as $code=>$delivery){?>
                <div class="box" ><?php echo $delivery['title'];?>:</div>
                <div class="box" >
                 <?php foreach($delivery['points'] as $key=>$point){
                 if(!isset($products[$point['delivery_id']])){
                 ?>
                 <span>
                <input type="checkbox"  name='delivery[]' data-key="<?php echo $point['delivery_id']; ?>" data-title="<?php echo $point['region_name'].'['.$delivery['title'].']'; ?>" >
                <a data-key="<?php echo $point['delivery_id']; ?>" data-title="<?php echo $point['region_name'].'['.$delivery['title'].']'; ?>"><?php echo $point['region_name']; ?></a> 
                </span>
                <?php } }?>
               </div>
                <?php }?>

                <div class="buttons" style="width: 100%; text-align: center;"> 
                菜品模版 <select id="model" name="model" style="width:auto;" >
                 <?php foreach($products as $dkey=> $ddata){?>
            <option value="<?php echo $dkey;?>"><?php echo $ddata['region_name'].'['.$deliverys[$ddata['delivery_code']]['title'].']';?></option>
            <?php }?>
                </select>
                <button id="addmodulebtn" class="btn btn-primary"> 添加选中到当前周期 </button>        </div>
            </div>
        </form>

    </div>
</div>


<script src="view/javascript/json3.min.js" type="text/javascript"></script>
<script src="view/javascript/handlebars-v2.0.0.js" type="text/javascript"></script>
<script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/jq.validate/1.13.1-pre/jquery.validate.js"
        type="text/javascript"></script>
<script type="text/javascript">
    $.widget('custom.catcomplete', $.ui.autocomplete, {
        _renderMenu: function (ul, items) {
            var self = this, currentCategory = '';

            $.each(items, function (index, item) {
                if (item.category != currentCategory) {
                    ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

                    currentCategory = item.category;
                }

                self._renderItem(ul, item);
            });
        }
    });
</script>
<script id="entry-template" type="text/x-handlebars-template">
    {{#each data}}
    <tr>
        <td class="right">{{sort_order}}</td>
        <td class="left">
            <a class="popup"
               href="index.php?route=catalog/product/update&product_id={{product_id}}">{{name}}</a>
        </td>
        <td class="left">{{sku}}</td>
        <td class="right">{{price}}</td>   
<?php 
      for ($i=$starttime;$i<=$endtime; $i= $i + 86400){?>
<?php if($i<strtotime(date('Y-m-d'))) {?>

                        <td class="right">{{date.<?php echo date('Y-m-d',$i);?>.quantity}}/{{date.<?php echo date('Y-m-d',$i);?>.stock}}</td>

<?php }else {?>

                        <td class="right">{{date.<?php echo date('Y-m-d',$i);?>.quantity}}/{{date.<?php echo date('Y-m-d',$i);?>.stock}} <input type="text" name=data[{{delivery_id}}][{{product_id}}][<?php echo date('Y-m-d',$i);?>][adder] data-key="{{delivery_id}}" data-index="{{sort_order}}" data-date="<?php echo date('Y-m-d',$i);?>" onblur="changepro($(this))" value='0' size=2 style="width:auto;" /></td>
<?php }?>                         
<?php }?> 
        <td class="right">[<a class="remove-item" data-pid="{{product_id}}" data-key="{{delivery_id}}">删除</a>]</td>
    </tr>
    {{/each}}
</script>
<script type="text/javascript">
    var id = '<?php echo (isset($result['id'])?$result['id']:'') ?>';
    var productsData = <?php echo json_encode($products); ?>;
console.log('productsData',productsData);

function changepro(obj){
	productsData[obj.data('key')]['data'][obj.data('index')-1]['date']=productsData[obj.data('key')]['data'][obj.data('index')-1]['date']||{};
	productsData[obj.data('key')]['data'][obj.data('index')-1]['date'][obj.data('date')]=productsData[obj.data('key')]['data'][obj.data('index')-1]['date'][obj.data('date')]||{};
	productsData[obj.data('key')]['data'][obj.data('index')-1]['date'][obj.data('date')]['quantity']=obj.val();
}



    $(document).ready(function () {
        $('#tabs a').tabs();
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});

        
        $('#tab-newsite a').bind("click", function () {
        	addmodule($(this));
        	$(this).parent().remove();
        });
        $('#addmodulebtn').bind("click", function () {
        	
        $('input[name="delivery[]"]:checked').each(function(n){
        	  
        	    var this$=$(this);
        	    console.log('this$',this$,n,$("#model").val());
        		addmodule(this$,$("#model").val());
        		this$.parent().remove();
    	    }); 
        return false;
        });
       
        
        

        var tpl = $("#entry-template").html();
        var template = Handlebars.compile(tpl);

        function renderProducts(key) {
        	key=key||false;
        	if(!key){
            
            for(key in productsData)
            {
            	var index=0;
            var data = _.map(productsData[key]['data'], function (item,key) {
                var p = _.clone(item);
                if (!!p.price) {
                    p.price = parseFloat(p.price.toString()).round(2);
                }
                index++;
                p.sort_order = index;
                //p.delivery_id=key;
                return p;
            });
            productsData[key]['data']=data;
            $('#main-tbody-'+key).html(template({data: data}));
            }}
        	else
        		{var index=0;
        		 var data = _.map(productsData[key]['data'], function (item,key) {
                     var p = _.clone(item);
                     if (!!p.price) {
                         p.price = parseFloat(p.price.toString()).round(2);
                     }
                     index++;
                     p.sort_order = index;
                     //p.delivery_id=key;
                     return p;
                 });
        		 productsData[key]['data']=data;
                 $('#main-tbody-'+key).html(template({data: data}));
        		
        		}
        }

        if (!!id) {
            renderProducts()
        }
        function addmodule(obj,model){
        	model=model||false;
        	$('#tab-newsite').before('<div id="tab-products-'+obj.data('key')+'" class="vtabs-content">\
                    <span class="help"><?php echo $text_period_help;?></span><br/>\
                    <table class="list" id="products">\
                        <thead>\
                        <tr><td class="right" width="100px">顺序</td>\
                            <td class="left">商品</td>\
                            <td class="left">型号</td>\
                            <td class="right">价格</td>\
                            <?php 
                            $starttime = strtotime($result['start_date']); 
                            $endtime = strtotime($result['end_date']); 
        $inum=0;
                            for ($i=$starttime;$i<=$endtime; $i= $i + 86400){
        $inum++;
                            ?>
                            <td class="right"><?php echo date('m/d',$i);?></td>\
                            <?php }?>
                            <td class="right">管理</td>\
                        </tr>\
                        </thead>\
                        <tbody id="main-tbody-'+obj.data('key')+'">\
                        </tbody>\
                        <tbody>\
                        <tr>\
                            <td colspan="1">\
                                <input type="text" placeholder="插入位置" style="margin: 5px 0;width: 100px;" id="insert-pos-'+obj.data('key')+'"/>\
                            </td>\
                            <td colspan="<?php echo $inum+4;?>">\
                                <input type="text" placeholder="输入名称" class="insert-field" data-key="'+obj.data('key')+'" style="margin: 5px 0;width: 200px;" id="insert-field-'+obj.data('key')+'"/>\
                            </td>\
                        </tr>\
                        </tbody>\
                    </table>\
                </div>');
        	
        	$('#addmodule').before('<a href="#tab-products-' + obj.data('key')+ '">' + obj.data('title') + '</a>');
        	$('#model').append('<option value="' + obj.data('key')+ '">' + obj.data('title') + '</option>');

        	
        	$('#tabs a').tabs();
        	insertAutoComplete();
        	
        	
        	productsData[obj.data('key')]=productsData[obj.data('key')]||{};
        	
        	if(model)
        		{
        		var newdata= $.extend(true, {}, productsData[model]['data']);
        		for(var i in newdata){
        			newdata[i]['delivery_id']=obj.data('key');
        		}
        		productsData[obj.data('key')]['data']=newdata||{};
        		}
        	else
        	    productsData[obj.data('key')]['data']=productsData[obj.data('key')]['data']||{};
        	
        	
        	productsData[obj.data('key')]['region_name']=obj.html();
        	renderProducts();
        	
        }
        function insertAutoComplete() {
            var insertField$ = $('.insert-field');
            insertField$.each(function(n){
 var insertField=$(this);
 
            	//console.log('insertField',insertField,insertField.data('key'));
      insertField.autocomplete({
                delay: 0,
                source: function (request, response) {

                    $.ajax({
                        url: 'index.php?route=catalog/product/autocomplete',
                        type: 'POST',
                        dataType: 'json',
                        data: 'filter_name=' + encodeURIComponent(request.term),
                        success: function (data) {
                        	
                            response($.map(data, function (item) {
                            	return {
                                    label: '['+item.sku+']'+item.name,
                                    value: item.product_id,
                                    raw: item
                                };
                            }));
                        }
                    });
                },
                select: function (event, ui) {
                    var pdata = ui.item;
                    console.log('productsData',insertField.data('key'));

                    if(IsContain(productsData[insertField.data('key')]['data'], pdata.raw.product_id)){
                        alert("重复菜品!");
                    }
                    else if(pdata.raw.status != 1){
                        alert("非启用菜品!");
                    }
                    else{
    
                    	var pos= document.getElementById('insert-pos-'+insertField.data('key')).value;
 
                    	if( pos > 0) {
                            productsData[insertField.data('key')]['data'].splice(pos-1, 0, {
                            	delivery_id:insertField.data('key'),
                                product_id: pdata.raw.product_id,
                                sku: pdata.raw.sku,
                                price: pdata.raw.price,
                                name: pdata.raw.name,
                                sort_order:0
                            });
                    	}
                    	else {
                            productsData[insertField.data('key')]['data'].push({
                            	delivery_id:insertField.data('key'),
                                product_id: pdata.raw.product_id,
                                sku: pdata.raw.sku,
                                price: pdata.raw.price,
                                name: pdata.raw.name,
                                sort_order:0
                            });
                    	}
                        renderProducts(insertField.data('key'));
                    }

                    insertField.val('');
                    return false;
                }
            }).bind("input.autocomplete", function () {
                $(this).autocomplete("search", this.value);
            });
            });
        }

        function IsContain(arr,value)
        {
          for(var i=0;i<arr.length;i++)
          {
             if(arr[i].product_id==value)
              return true;
          }
          return false;
        }

        $('.remove-item').live('click', function () {
            var this$ = $(this);
      
//            if (confirm('确认删除该项？')) {
                var pid = this$.data('pid');
                _.remove(productsData[this$.data('key')]['data'], function (item) {
                    return item.product_id == pid;
                });
                renderProducts(this$.data('key'));
//            }
        });

        insertAutoComplete();

        $('#form').submit(function () {
           /* this$ = $(this);
            var formdata={};
            for(key in productsData)
            {
            	 var productIds = _.map(productsData[key]['data'], function (item) {
                 	
                 	var prodata={};
                 	prodata.product_id=item.product_id;
                 	prodata.date=item.date;
                     return prodata;
                 });
            	
            	 formdata[key]=productIds;
            }

            this$.find('[name="productIds"]').val(JSON.stringify(formdata));
            */
        });

        jQuery.extend(jQuery.validator.messages, {
            required: "此项必填",
//        remote: "Please fix this field.",
            email: "请输入正确的邮箱地址"
//        url: "Please enter a valid URL.",
//        date: "Please enter a valid date.",
//        dateISO: "Please enter a valid date (ISO).",
//        number: "Please enter a valid number.",
//        digits: "Please enter only digits.",
//        creditcard: "Please enter a valid credit card number.",
//        equalTo: "Please enter the same value again.",
//        accept: "Please enter a value with a valid extension.",
//        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
//        minlength: jQuery.validator.format("Please enter at least {0} characters."),
//        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
//        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
//        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
//        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
        });
        $("#form").validate({
            ignore: []
        });


        $('#save-btn').click(function () {
            $('#form').submit();
        });
        
    });
</script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
CKEDITOR.replace('description', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
</script>