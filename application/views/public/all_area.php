<script src="<?= static_url();?>js/jquery-1.11.3.min.js"/></script>
<style>
.area_province_div{
	margin-left:20px;
}

.area_city_div{
	margin-left:20px;
}
.area_county_div_parent{
	background:#eee;
	width:180px;
}
.area_county_div{
	height:350px;
	width:100%;
	background:#fff;
	overflow-y:scroll;
	padding:10px 10px;
	border:1px solid #e1e1e1;
}

.area_county_div span{
	cursor:pointer;
}
.area_select_lable_tao{
	font-size:12px;
}
.area_province_div span{
	font-size:12px;
}
.area_select_lable_tao{
	cursor:pointer;
}

.confimSelectCity_class{
	margin-top:5px; 
	margin-left:120px;
	margin-bottom:5px;
}

</style>
<div class="area_county_div_parent">
	<div class="area_county_div">
		<label class="area_select_lable_tao"><input type="checkbox" class="all_country" onclick="trigger_province_select(this)" />全国</label>
		<div class="area_province_div">
			<?php foreach($all_area as $province_id => $area):?>
			<div>
				<label class="area_select_lable_tao"><input class="all_pro all_provence_<?= $province_id ?>" type="checkbox" onclick="trigger_city_select(this, <?= $province_id ?>)"   /></label><span onclick="trigger_city_div(<?= $province_id ?>)"><?= $area[0]['province_name']?></span>
				<div style="display:none;" class="area_city_div_<?= $province_id ?> area_city_div">
					<?php foreach($area as $city):?>
					<div>
						<label class="area_select_lable_tao"><input class="all_pro all_city_<?= $province_id ?> all_city_class city_class_<?= $city['district_id'] ?>" data-district_id='<?= $city['district_id'] ?>' <?php if(in_array($city['district_id'], $districtIdList)){echo 'checked';}?> data-parent_id='<?= $province_id ?>' type="checkbox" value="<?= $city['district_name']?>" /><?= $city['district_name']?></label>
					</div>
					<?php endforeach ?>
				</div>
			</div>
			<?php endforeach ?>
		</div>
	</div>
</div>
<script>
var SLIDE_SPEED = 200;
//点击全国后展开
/*
function trigger_province_div(){
	$(".area_province_div").slideToggle(SLIDE_SPEED);
}
*/

//点击全国checkbox后全选反选
function trigger_province_select(obj){
	$(".all_pro").prop("checked", $(obj).prop("checked"));
}

//点击省份后展开
function trigger_city_div(city_id){
	$(".area_city_div_"+city_id).slideToggle(SLIDE_SPEED);
}
//点击省份后全选反选
function trigger_city_select(obj, city_id){
	$(".all_city_"+city_id).prop("checked", $(obj).prop("checked"));
	checkCitySelectAll();
}

//判断是否全国选择 & 该省份全选
function checkCitySelectAll(){
	var all_country_flag = true;
	var provence_flag_arr = {};
	$(".all_city_class").each(function(){
		var parent_id = $(this).data("parent_id");
		if(provence_flag_arr[parent_id] === undefined){
			provence_flag_arr[parent_id] = true;
		}
		if($(this).prop("checked") == false){
			all_country_flag = false;
			provence_flag_arr[parent_id] = false;
		}
	})
	for(var i in provence_flag_arr){
		$(".all_provence_"+i).prop("checked", provence_flag_arr[i]);
	}
	$(".all_country").prop("checked", all_country_flag);
}

$(".all_city_class").click(function(){
	checkCitySelectAll();
})
$(function(){
	checkCitySelectAll();
})
</script>