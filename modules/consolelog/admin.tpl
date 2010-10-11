{if $item == "permissions"}
The following permissions can be set:
<hr />
<table border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
    <tr style="font-weight: bold;">
        <td>Role</td>
        <td>Create</td>
        <td>Read</td>
        <td>Update</td>
        <td>delete</td>
    </tr>
    
{foreach $aPermissions d}
    <tr>
        <td>{$d.name}</td>
        <td><a href="/Admin/acl/{$moduleName}/{$d.name}/create/{if $d.create == 1}deny{else}allow{/if}">{$d.create}</a></td>
        <td><a href="/Admin/acl/{$moduleName}/{$d.name}/read/{if $d.read == 1}deny{else}allow{/if}">{$d.read}</a></td>
        <td><a href="/Admin/acl/{$moduleName}/{$d.name}/update/{if $d.update == 1}deny{else}allow{/if}">{$d.update}</a></td>
        <td><a href="/Admin/acl/{$moduleName}/{$d.name}/delete/{if $d.delete == 1}deny{else}allow{/if}">{$d.delete}</a></td>
    </tr>
{/foreach}
</table>
{/if}