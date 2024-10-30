

<?php if($type == 'textarea'){?>
    <textarea name="<?php echo esc_attr($fieldApiName);?>" class="form-control" <?php echo esc_attr($mandatory);?>></textarea>
<?php }else if($type == 'text'){?>
    <input type="text" name="<?php echo esc_attr($fieldApiName); ?>" placeholder="<?php echo esc_attr(str_replace('_', ' ', $fieldApiName)) ?>" class="form-control" <?php echo esc_attr($mandatory);?> >
<?php }elseif($type == 'email'){?>
    <input type="email" name="<?php echo esc_attr($fieldApiName); ?>" placeholder="<?php echo esc_attr(str_replace('_', ' ', $fieldApiName) )?>" class="form-control" <?php echo esc_attr($mandatory);?> >
<?php }elseif($type == 'date'){?>
    <input type="date" name="<?php echo esc_attr($fieldApiName); ?>" placeholder="<?php echo esc_attr(str_replace('_', ' ', $fieldApiName) )?>" class="form-control" <?php echo esc_attr($mandatory);?> >
    
<?php }elseif($type == 'datetime'){?>
    <input type="datetime-local" name="<?php echo esc_attr($fieldApiName); ?>" placeholder="<?php echo esc_attr(str_replace('_', ' ', $fieldApiName) ) ?>" class="form-control" <?php echo esc_attr($mandatory);?> >

<?php }elseif($type == 'picklist'){?>
    <select  name="<?php echo esc_attr($fieldApiName); ?>" placeholder="<?php echo esc_attr(str_replace('_', ' ', $fieldApiName)) ?>" class="form-control  ccg-basic-single"  <?php echo esc_attr($mandatory);?> >
        <option>--Select <?php echo esc_attr(str_replace('_', ' ', $fieldApiName)) ?>--</option>
        <?php 
        $pick_list_values = $fieldList[$fieldApiName]['pick_list_values'];
        foreach ($pick_list_values as $option) {
            $ac_value = $option["actual_value"];
            $d_value = $option["display_value"];
            
            echo '<option value="'.$d_value.'" '.$slctd.'>'.$d_value.'</option>';

        } ?>
    </select>
<?php }elseif($type == 'multiselectpicklist'){?>
    <select multiple name="<?php echo esc_attr($fieldApiName); ?>[]" class="form-control ccg-basic-multiple"  <?php echo esc_attr($mandatory);?>>
        
        <?php 
        $pick_list_values = $fieldList[$fieldApiName]['pick_list_values'];
        foreach ($pick_list_values as $option) {
            $ac_value = $option["actual_value"];
            $d_value = $option["display_value"];
            
            echo '<option value="'.$d_value.'">'.$d_value.'</option>';

        } ?>
    </select>
<?php }elseif($type == 'boolean'){?>
    <input type="checkbox" class="form-control"  name="<?php echo esc_attr($fieldApiName); ?>"> 
<?php }else if($type == 'integer'){ ?>
    <input type="number" name="<?php echo esc_attr($fieldApiName); ?>" class="form-control" <?php echo esc_attr($mandatory);?> >
<?php }else if($type == 'lookup'){ ?>

<div class="input-group" id="<?php echo esc_attr($fieldApiName); ?>_lookup">
    <input type="text" readonly name="<?php echo esc_attr($fieldApiName); ?>" class="form-control showDialogLookup record_name" <?php echo esc_attr($mandatory);?> value="">
    <input type="hidden" name="<?php echo esc_attr($fieldApiName); ?>___id" class="record_id" value="" >
    <input type="hidden" class="modulename" value="<?php echo esc_attr($lookupModule); ?>" >
    <input type="hidden" class="fieldname" value="<?php echo esc_attr($fieldApiName); ?>" >
</div>

<?php }else if($type == 'fileupload'){ ?>

    <div class="input-group" id="<?php echo esc_attr($fieldApiName); ?>_fileupload">
        <input type="file" name="<?php echo esc_attr($fieldApiName); ?>___file">
        <input type="hidden" name="<?php echo esc_attr($fieldApiName); ?>">
    </div>

<?php }else{ ?>
    <input type="text" name="<?php echo esc_attr($fieldApiName); ?>" class="form-control" <?php echo esc_attr($mandatory);?> >
<?php } ?>
