<?php echo $header; ?>
<div class="container">
    <div id="content" class="content">
        <h1 class="border-bottom pb-3 mb-3"><?php echo $heading_title; ?></h1>
        <div class="clearfix mb-3">
            <div class="float-start"><a href="<?php echo $cancel; ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-long-arrow-alt-left fa-fw"></i> <?php echo lang('Button.cancel'); ?></a></div>
            <div class="float-end"><a href="<?php echo $add; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus fa-fw"></i> <?php echo lang('Button.add'); ?></a></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col"><?php echo lang('Column.address'); ?></th>
                        <th scope="col" class="text-end"><?php echo lang('Column.action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customer_addresses)) { ?>
                        <?php foreach ($customer_addresses as $customer_address) { ?>
                        <tr>
                            <td><?php echo $customer_address['address']; ?></td>
                            <td class="text-end"><a href="<?php echo $customer_address['edit']; ?>" class="btn btn-primary"><?php echo lang('Button.edit'); ?></a> <a href="<?php echo $customer_address['delete']; ?>" class="btn btn-danger" onclick="return confirm('<?php echo lang('Text.are_you_sure'); ?>');"><?php echo lang('Button.delete'); ?></a></td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td colspan="2" class="text-center"><?php echo lang('Error.no_data_found'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo $footer; ?>
