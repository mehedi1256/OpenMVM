<?php echo $header; ?>
<?php echo $sidemenu; ?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="content-wrapper min-vh-100">
	<!-- Heading Container -->
	<section class="heading-container bg-dark p-3">
		<h2 class="heading-title text-white"><i class="fas fa-user-secret fa-fw"></i> <?php echo $heading_title; ?></h2>
		<div class="heading-lead lead text-white"><?php echo $lead; ?></div>
	</section>
  <!-- /.heading-container -->

	<!-- Breadcrumb -->
	<?php if ($breadcrumbs) { ?>
	<section id="breadcrumb" class="bg-light p-3 mb-3">
		<nav aria-label="breadcrumb">
		  <ol class="breadcrumb small p-0 m-0">
		  	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		  		<?php if ($breadcrumb['active']) { ?>
		    	<li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrumb['text']; ?></li>
	  			<?php } else { ?>
		    	<li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	  			<?php } ?>
		  	<?php } ?>
		  </ol>
		</nav>
  </section>
	<?php } ?>
	<!-- /.breadcrumb -->

	<!-- Notification -->
	<?php if ($success || $error) { ?>
	<section id="notification" class="notification px-3">
		<?php if ($success) { ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		  <?php echo $success; ?> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php } ?>
		<?php if ($error) { ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
		  <?php echo $error; ?> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php } ?>
  </section>
	<?php } ?>
	<!-- /.notification -->

	<!-- Content -->
	<section class="content px-3">
    <?php echo form_open($action); ?>
    <div class="clearfix mb-3">
	    <div class="float-end">
	      <button type="submit" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('Button.button_save', array(), $lang->getBackEndLocale()); ?>"><i class="fas fa-save"></i></button>
	      <a href="<?php echo base_url($_SERVER['app.adminDir'] . '/administrator/groups/' . $administrator_token); ?>" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('Button.button_cancel', array(), $lang->getBackEndLocale()); ?>"><i class="fas fa-long-arrow-alt-left"></i></a>
	    </div>
    </div>
		<div class="accordion" id="accordionAdministratorGroup">
		  <div class="accordion-item">
		    <h2 class="accordion-header" id="headingAdministratorGroup">
		      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdministratorGroup" aria-expanded="true" aria-controls="collapseAdministratorGroup">
		        <i class="fas fa-user-secret fa-fw"></i>&nbsp;&nbsp;<?php echo $heading_title; ?>
		      </button>
		    </h2>
		    <div id="collapseAdministratorGroup" class="accordion-collapse collapse show" aria-labelledby="headingAdministratorGroup" data-bs-parent="#accordionAdministratorGroup">
		      <div class="accordion-body">

		        <ul class="nav nav-tabs" id="administratorGroupTab" role="tablist">
		          <li class="nav-item">
		            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><?php echo lang('Tab.tab_general', array(), $lang->getBackEndLocale()); ?></a>
		          </li>
		        </ul>
		        <div class="tab-content mt-3" id="administratorGroupTabContent">
		          <div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
		            <fieldset>
							  	<div class="form-floating mb-3">
									  <input type="text" name="name" value="<?php echo $name; ?>" class="form-control<?php if ($validation->hasError('name')) { ?> is-invalid<?php } ?>" id="input-name" placeholder="<?php echo lang('Entry.entry_name', array(), $lang->getBackEndLocale()); ?>">
									  <label for="input-name"><?php echo lang('Entry.entry_name', array(), $lang->getBackEndLocale()); ?></label>
									  <?php if ($validation->hasError('name')) { ?>
	                  <div class="text-danger small"><?php echo $validation->getError('name'); ?></div>
	                	<?php } ?>
									</div>
		              <div class="form-group mb-3">
	                  <div class="card bg-light" style="height: 250px; overflow: auto;">
	                    <div class="card-body">
	                    	<h5 class="card-title"><?php echo lang('Entry.entry_access_permission', array(), $lang->getBackEndLocale()); ?></h5>
	                      <?php foreach ($permissions as $permission) { ?>
	                      <div class="checkbox">
	                        <label>
	                          <?php if (in_array($permission, $access)) { ?>
	                          <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
	                          <?php echo $permission; ?>
	                          <?php } else { ?>
	                          <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
	                          <?php echo $permission; ?>
	                          <?php } ?>
	                        </label>
	                      </div>
	                      <?php } ?>
	                    </div>
	                  </div>
	                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="clickable"><?php echo lang('Text.text_select_all', array(), $lang->getBackEndLocale()); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="clickable"><?php echo lang('Text.text_unselect_all', array(), $lang->getBackEndLocale()); ?></a>
		              </div>
		              <div class="form-group">
	                  <div class="card bg-light" style="height: 250px; overflow: auto;">
	                    <div class="card-body">
												<h5 class="card-title"><?php echo lang('Entry.entry_modify_permission', array(), $lang->getBackEndLocale()); ?></h5>
	                      <?php foreach ($permissions as $permission) { ?>
	                      <div class="checkbox">
	                        <label>
	                          <?php if (in_array($permission, $modify)) { ?>
	                          <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
	                          <?php echo $permission; ?>
	                          <?php } else { ?>
	                          <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
	                          <?php echo $permission; ?>
	                          <?php } ?>
	                        </label>
	                      </div>
	                      <?php } ?>
	                    </div>
	                  </div>
	                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="clickable"><?php echo lang('Text.text_select_all', array(), $lang->getBackEndLocale()); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="clickable"><?php echo lang('Text.text_unselect_all', array(), $lang->getBackEndLocale()); ?></a>
		              </div>
		            </fieldset>
		          </div>
		        </div>

		      </div>
		    </div>
		  </div>
		</div>
    <?php echo form_close(); ?>
	</section>
  <!-- /.content -->
<?php echo $footer; ?>
