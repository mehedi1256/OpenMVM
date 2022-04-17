<?php echo $header; ?><?php echo $column_left; ?>
<div class="container-fluid">
    <div id="content" class="content">
        <?php if ($breadcrumbs) { ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <?php if ($breadcrumb['active']) { ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrumb['text']; ?></li>
                    <?php } else { ?>
                    <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                <?php } ?>
            </ol>
        </nav>
        <?php } ?>
        <div class="card border-0 shadow heading mb-3">
            <div class="card-body">
                <h3 class="card-title"><i class="fas fa-user-tie fa-fw"></i> <?php echo $heading_title; ?></h3>
            </div>
        </div>
        <?php if ($error_warning) { ?>
        <div class="alert alert-warning alert-dismissible border-0 shadow fade show" role="alert">
            <?php echo $error_warning; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success alert-dismissible border-0 shadow fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>
        <?php echo form_open($action, ['id' => 'form-administrator-group']); ?>
        <div class="card shadow list">
            <div class="card-header clearfix"><h5 class="pt-1 float-start"><i class="fas fa-edit fa-fw"></i> <?php echo $sub_title; ?></h5> <div class="float-end"><button type="submit" class="btn btn-outline-primary btn-sm"><i class="fas fa-save fa-fw"></i></button> <a href="<?php echo $cancel; ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-long-arrow-alt-left fa-fw"></i></a></div></div>
            <div class="card-body">
                <fieldset>
                    <div class="mb-3 required">
                        <label for="input-name" class="form-label"><?php echo lang('Entry.name'); ?></label>
                        <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" placeholder="<?php echo lang('Entry.name'); ?>">
                        <?php if (!empty($error_name)) { ?><div class="text-danger small"><?php echo $error_name; ?></div><?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="input-access" class="form-label"><?php echo lang('Entry.access'); ?></label>
                                <div class="card card-body mb-3">
                                    <?php foreach ($permissions as $permission) { ?>
                                    <div class="form-check">
                                        <?php if (in_array($permission, $access)) { ?>
                                        <input class="form-check-input" type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" id="access-<?php echo str_replace('/', '-', $permission); ?>" checked="checked">
                                        <?php } else { ?>
                                        <input class="form-check-input" type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" id="access-<?php echo str_replace('/', '-', $permission); ?>">
                                        <?php } ?>
                                        <label class="form-check-label" for="access-<?php echo str_replace('/', '-', $permission); ?>"><?php echo $permission; ?></label>
                                    </div>                                    
                                    <?php } ?>
                                </div>
                                <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="btn btn-link btn-sm"><?php echo lang('Text.select_all'); ?></button> / <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="btn btn-link btn-sm"><?php echo lang('Text.unselect_all'); ?></button>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="input-modify" class="form-label"><?php echo lang('Entry.modify'); ?></label>
                                <div class="card card-body mb-3">
                                    <?php foreach ($permissions as $permission) { ?>
                                    <div class="form-check">
                                        <?php if (in_array($permission, $modify)) { ?>
                                        <input class="form-check-input" type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" id="modify-<?php echo str_replace('/', '-', $permission); ?>" checked="checked">
                                        <?php } else { ?>
                                        <input class="form-check-input" type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" id="modify-<?php echo str_replace('/', '-', $permission); ?>">
                                        <?php } ?>
                                        <label class="form-check-label" for="modify-<?php echo str_replace('/', '-', $permission); ?>"><?php echo $permission; ?></label>
                                    </div>                                    
                                    <?php } ?>
                                </div>
                                <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="btn btn-link btn-sm"><?php echo lang('Text.select_all'); ?></button> / <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="btn btn-link btn-sm"><?php echo lang('Text.unselect_all'); ?></button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php echo $footer; ?>
