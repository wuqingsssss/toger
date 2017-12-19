<div class="box">
    <div class="heading">
      <h2><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h2>
      <div class="buttons" style="margin-bottom:10px;">
      <button onclick="location = '<?php echo $cancel; ?>';" class="btn">返回询价单列表</button>
      </div>
    </div>
    <div class="content">
     <div class="vtabs"><a href="#tab-order">询单详情</a>
     </div>
      <div id="tab-order" class="vtabs-content" >
        <table class="form">
          <tr>
            <td>联系人</td>
            <td><?php echo $name; ?></td>
          </tr>
          <tr>
            <td>联系电话</td>
            <td><?php echo $telephone; ?></td>
          </tr>
          <tr>
            <td>详细说明</td>
            <td><?php echo $description; ?></td>
          </tr>
          <tr>
            <td>附件相关</td>
            <td></td>
          </tr>
          
          <tr>
            <td>询单时间</td>
            <td><?php echo $date_added; ?></td>
          </tr>
          
        </table>
      </div>
    
      <div id="tab-product" class="vtabs-content">
        <table id="product" class="list">
          <thead>
            <tr>
              <td class="left">产品名称</td>
              <td class="left">产品数量</td>
              <td class="right">单位</td>
              <td class="right">目标单价</td>
            </tr>
          </thead>
          <?php foreach ($products as $product) { ?>
          <tbody id="product-row<?php echo $product['enquiry_product_id']; ?>">
            <tr>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['quantity']; ?></td>
              <td class="right"><?php echo $product['unit']; ?></td>
              <td class="right"><?php echo $product['price']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
