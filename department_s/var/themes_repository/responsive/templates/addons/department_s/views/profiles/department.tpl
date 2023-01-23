<div id="profiles_{$department_data.department_id}">

    <div class="ty-feature">
        {if $department_data.main_pair}
        <div class="ty-feature__image">
            {include 
            file="common/image.tpl" 
            images=$department_data.main_pair
            }
        </div>
        {/if}

        <div class="ty-feature__description ty-wysiwyg-content">
            <h4>{__("departments.lead_name")}:</h4>
            <span>{$u_lead}</span> 	
        </div>

        <div class="ty-feature__description ty-wysiwyg-content">
            <h4>{__("departments.staff_name")}:</h4>
        </div>
        {foreach from=$department_data['staff_id'] item="staff_id"}
            {if !empty($staff_id)}
                {$staff_user = fn_get_user_name($staff_id)}
                <div>
                    <span>{$staff_user}</span> 
                </div> 
            {/if}
        {/foreach} 

        <div class="ty-feature__description ty-wysiwyg-content">
            <h4>{__("description")}:</h4>
            {$department_data.description nofilter}
        </div>

    </div>
</div>

{capture name="mainbox_title"}{$department_data.name nofilter}{/capture}