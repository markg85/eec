{if $subPath == "" && ( $item == "overview" || $item == "")}
    {foreach $aMenuItems d}
        <a href="{eec_rest_url "/Admin/$moduleName/$d.url"}">{$d.name}</a><br />
    {/foreach}
    
    <h1>Template item overview</h1>
    <table border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
        <tr style="font-weight: bold;">
            <td>Name</td>
            <td>Remove</td>
        </tr>
    {foreach $aTemplateItems d}
        
        <tr>
            <td><a href="{eec_rest_url "/Admin/$moduleName/edit/$d.id"}">{$d.template_name}</a></td>
            <td><a href="{eec_rest_url "/Admin/$moduleName/delete/$d.id"}"}">Delete</a></td>
        </tr>

    {/foreach}
    </table>
{else if $item == "add"}
<form method="post" action="#">
    <table border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
        <tr>
            <td>Template name</td>
            <td><input type="text" name="template_name" /></td>
        </tr>
        <tr>
            <td>Variable name 1</td>
            <td><input type="text" name="var_1" /></td>
        </tr>
        <tr>
            <td>Variable name 2</td>
            <td><input type="text" name="var_2" /></td>
        </tr>
        <tr>
            <td>Variable name 3</td>
            <td><input type="text" name="var_3" /></td>
        </tr>
        <tr>
            <td>Variable name 4</td>
            <td><input type="text" name="var_4" /></td>
        </tr>
        <tr>
            <td>Variable name 5</td>
            <td><input type="text" name="var_5" /></td>
        </tr>
        <tr>
            <td>Template</td>
            <td>
                Note: it's good practice to leave out any style settings and apply those to the style.css file!
                <textarea name="template_data" rows="20"></textarea>
            </td>
        </tr>
        <tr>
            <td>Your current style.css</td>
            <td>
                <textarea name="style_data" rows="20"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="Save" /> <input type="button" value="Preview" /></td>
        </tr>
    </table>
</form>
{else if $subpath == "edit"}
<form method="post" action="#">
    <table border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
        <tr>
            <td>Template name</td>
            <td><input type="text" name="template_name" value="{$aTemplateMetadata.0.template_name}" /></td>
        </tr>
        <tr>
            <td>Variable name 1</td>
            <td><input type="text" name="var_1" value="{$aTemplateMetadata.0.template_var_1}" /></td>
        </tr>
        <tr>
            <td>Variable name 2</td>
            <td><input type="text" name="var_2" value="{$aTemplateMetadata.0.template_var_2}" /></td>
        </tr>
        <tr>
            <td>Variable name 3</td>
            <td><input type="text" name="var_3" value="{$aTemplateMetadata.0.template_var_3}" /></td>
        </tr>
        <tr>
            <td>Variable name 4</td>
            <td><input type="text" name="var_4" value="{$aTemplateMetadata.0.template_var_4}" /></td>
        </tr>
        <tr>
            <td>Variable name 5</td>
            <td><input type="text" name="var_5" value="{$aTemplateMetadata.0.template_var_5}" /></td>
        </tr>
        <tr>
            <td>Template</td>
            <td>
                Note: it's good practice to leave out any style settings and apply those to the style.css file!
                <textarea name="template_data" rows="20">{$aTemplateMetadata.0.raw_data}</textarea>
            </td>
        </tr>
        <tr>
            <td>Your current style.css</td>
            <td>
                <textarea name="style_data" rows="20"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="Save" /> <input type="button" value="Preview" /></td>
        </tr>
    </table>
</form>
{else if $subpath == "delete"}
{/if}