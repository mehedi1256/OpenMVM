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
                <h3 class="card-title"><i class="fas fa-ruler-combined fa-fw"></i> <?php echo $heading_title; ?></h3>
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
        <?php echo form_open($action, ['id' => 'form-length-class']); ?>
        <div class="card shadow list">
            <div class="card-header clearfix"><h5 class="pt-1 float-start"><i class="fas fa-list fa-fw"></i> <?php echo lang('Heading.list'); ?></h5> <div class="float-end"><a href="<?php echo $add; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus fa-fw"></i></a> <button type="button" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt fa-fw" onclick="confirm('<?php echo lang('Text.are_you_sure'); ?>') ? $('#form-length-class').submit() : false;"></i></button> <a href="<?php echo $cancel; ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-long-arrow-alt-left fa-fw"></i></a></div></div>
            <div class="card-body">
                <table class="table">
                    <caption><?php echo lang('Caption.list_of_length_classes'); ?></caption>
                    <thead>
                        <tr>
                            <th scope="col"><input class="form-check-input" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                            <th scope="col"><?php echo lang('Column.title'); ?></th>
                            <th scope="col"><?php echo lang('Column.unit'); ?></th>
                            <th scope="col"><?php echo lang('Column.value'); ?></th>
                            <th scope="col" class="text-end"><?php echo lang('Column.action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($length_classes) { ?>
                            <?php foreach ($length_classes as $length_class) { ?>
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <?php if (in_array($length_class['length_class_id'], $selected)) { ?>
                                        <input class="form-check-input" type="checkbox" name="selected[]" value="<?php echo $length_class['length_class_id']; ?>" id="input-selected-<?php echo $length_class['length_class_id']; ?>" checked="checked">
                                        <?php } else { ?>
                                        <input class="form-check-input" type="checkbox" name="selected[]" value="<?php echo $length_class['length_class_id']; ?>" id="input-selected-<?php echo $length_class['length_class_id']; ?>">
                                        <?php } ?>
                                    </div>                                        
                                </th>
                                <td><?php echo $length_class['title']; ?> <?php if ($length_class['length_class_id'] == $default) { ?><strong>( <?php echo lang('Text.default'); ?> )</strong><?php } ?></td>
                                <td><?php echo $length_class['unit']; ?></td>
                                <td><?php echo $length_class['value']; ?></td>
                                <td class="text-end"><a href="<?php echo $length_class['href']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit fa-fw"></i></a></td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td colspan="3" class="text-muted text-center"><?php echo lang('Error.no_data_found'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php echo $footer; ?>
