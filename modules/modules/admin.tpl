{if $subPath == "" && ( $item == "overview" || $item == "")}
<h1>Welcome on the <i>{$aModuleInfo.modulename}</i> overview page.</h1>
The following modules are installed: <p />
<table border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
    <tr style="font-weight: bold;">
        <td>Module</td>
        <td>State</td>
        <td>REST</td>
        <td>Permissions</td>
        <td>Uninstall</td>
    </tr>
{foreach $aInstalledModules d}
    
    <tr>
        <td><a href="{eec_rest_url "/Admin/$d.modulerestname/"}">{$d.modulename}</a></td>
        <td>
            {if $d.enabled}
                <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/disable"}">Disable</a>
            {else}
                <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/enable"}">Enable</a>
            {/if}
        </td>
        <td>
            {if $d.restenabled}
                <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/disablerest"}">Disable</a>
            {else}
                <a href="{eec_rest_url "/Admin/modules/$d.modulerestname/enablerest"}">Enable</a>
            {/if}
        </td>
        <td><a href="{eec_rest_url "/Admin/acl/$d.modulerestname/permissions"}">Manage permissions</a></td>
        <td><a href="{eec_rest_url "/Admin/modules/$d.modulerestname/uninstall"}">uninstall</a></td>
    </tr>

{/foreach}
</table>

<p />
{if empty($aInstallableModules)}
There are no other modules to install.
{else}
The following modules can be installed: <br />
{foreach $aInstallableModules d}
    
    {$d.module} - 
    <a href="{eec_rest_url "/Admin/modules/{$d.module}/install"}">install</a>
    <br />

{/foreach}

{/if}