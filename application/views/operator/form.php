
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i> 修改后台用户
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
						<span class="required" aria-required="true">*</span> 登录名
					</label>

					<div class="col-md-4">
						<input type="text" value="<?php echo!empty($operator['0']['operator_name']) ? $operator['0']['operator_name'] : ''; ?>" placeholder="用户登录名" class="form-control required" data-required="1" maxlength="128" name="operator_name" aria-required="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						<span class="required" aria-required="true">*</span> 用户姓名
					</label>

					<div class="col-md-4">
						<input type="text" value="<?php echo!empty($operator['0']['name']) ? $operator['0']['name'] : ''; ?>" placeholder="用户姓名" class="form-control required" data-required="1" maxlength="128" name="name" aria-required="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						手机号
					</label>

					<div class="col-md-4">
						<input type="text" value="<?php echo!empty($operator['0']['mobile']) ? $operator['0']['mobile'] : ''; ?>" placeholder="手机号" class="form-control" data-required="1" maxlength="128" name="mobile" aria-required="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						<span class="required" aria-required="true">*</span> 用户邮箱
					</label>

					<div class="col-md-4">
						<input type="text" value="<?php echo!empty($operator['0']['email']) ? $operator['0']['email'] : ''; ?>" placeholder="用户邮箱" class="form-control email required" data-required="1" maxlength="128" name="email" aria-required="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						<span class="required" aria-required="true">*</span>密码
					</label>

					<div class="col-md-4">
						<input type="text" value="" placeholder="用户密码" class="form-control" data-required="1" maxlength="128" name="password" aria-required="true">
						<span class="help-block">不修改密码不需要填写</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><span class="required" aria-required="true">*</span>权限组</label>
					<div class="col-md-4">
						<select class="form-control input-sm" name="role_id">
							<?php foreach ($roles as $key => $value) {?>
								<option value="<?php echo $value['id']?>" <?php if(!empty($operator['0']['role_id']) && $value['id']==$operator['0']['role_id']){echo "selected";}?>><?php echo $value['name']?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						最近登录ip
					</label>

					<div class="col-md-4">
						<input type="text" value="<?php echo!empty($operator['0']['login_ip']) ? $operator['0']['login_ip'] : ''; ?>"  class="form-control" data-required="1" maxlength="128" name="login_ip" disabled="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						最近登录时间
					</label>

					<div class="col-md-4">
						<input type="text" value="<?php echo!empty($operator['0']['login_time']) ? $operator['0']['login_time'] : ''; ?>"  class="form-control" data-required="1" maxlength="128" name="login_time" disabled="true">
					</div>
				</div>
				<input type="hidden" name="operator_id" value="<?php echo!empty($operator['0']['operator_id']) ? $operator['0']['operator_id'] : ''; ?>">
			</div>
			<div class="form-actions right">
				<div class="col-md-offset-3 col-md-9">
					<button class="btn green" type="submit"><i class="fa fa-check"></i> 保存</button>
					<a class="JS_close_win btn default" href="javascript:void(0);">取消</a>
				</div>
			</div>
		</form>    </div>
</div>