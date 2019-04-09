<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i> 修改密码
        </div>
    </div>
    <div class="portlet-body form">
        <form class="JS_form_valid form-horizontal form-bordered" accept-charset="utf-8" id="operator_form" method="post" action="" novalidate="novalidate">
			<div class="form-body">
				<div class="form-actions right1">
					<button class="btn green" type="submit"><i class="fa fa-check"></i> 保存</button>
					<a class="JS_close_win btn default" href="javascript:void(0);">取消</a>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label">
						<span class="required" aria-required="true">*</span> 用户名
					</label>

					<div class="col-md-4">
						<input type="text" disabled="true" value="<?php echo $this->session->operator_name;?>" placeholder="用户登录名" class="form-control required" data-required="1" maxlength="128" name="operator_name" aria-required="true">
					</div>
				</div>

			    <div class="form-group">
					<label class="col-md-3 control-label">
						<span class="required" aria-required="true">*</span>密码
					</label>

					<div class="col-md-4">
						<input type="text" value="" placeholder="用户密码" class="form-control required" data-required="1" maxlength="128" name="password" aria-required="true">
						
					</div>
				</div>
				<input type="hidden" name="operator_id" value="<?php echo $this->session->operator_id;?>">
			</div>
			<div class="form-actions right">
				<div class="col-md-offset-3 col-md-9">
					<button class="btn green" type="submit"><i class="fa fa-check"></i> 保存</button>
					<a class="JS_close_win btn default" href="javascript:void(0);">取消</a>
				</div>
			</div>
        </form>    </div>
</div>
