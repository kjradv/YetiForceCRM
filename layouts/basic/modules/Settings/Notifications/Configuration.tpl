{*<!-- {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} --!>*}
{strip}
	<div class="widget_header row">
		<div class="col-md-6">
			{include file='BreadCrumbs.tpl'|@vtemplate_path:$MODULE}
		</div>
	</div>
	<div class="col-xs-12 listModules paddingLRZero">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th class="col-xs-2">
						{vtranslate('LBL_MODULE', $QUALIFIED_MODULE)}
					</th>
					<th>
						{vtranslate('Share with users', $QUALIFIED_MODULE)}
					</th>
					<th class="col-xs-1">
						{vtranslate('LBL_ACTIONS', $QUALIFIED_MODULE)}
					</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$LIST_MODULES item=MODULE_ITEM key=MODULE_NAME}
					<tr class="trRow" data-module="{$MODULE_NAME}">
						<td>
							{vtranslate($MODULE_NAME, $MODULE_NAME)}
						</td>
						<td>
							<select multiple class="chzn-select form-control col-md-4 shareOwners">
								<optgroup>
									{foreach from=$LIST_USERS item=USER_MODEL}
										<option value="{$USER_MODEL->get('id')}" 
												{if in_array($USER_MODEL->get('id'), $LIST_MODULES_USERS[$MODULE_NAME])} selected{/if}>
											{$USER_MODEL->get('user_name')}
										</option>
									{/foreach}
								</optgroup>
							</select>
						</td>
						<td>
							<div class="text-center">
								<button class="btn btn-success btn-sm selectAll" title="{vtranslate('LBL_SELECT_ALL',$QUALIFIED_MODULE)}">
									<span class="glyphicon glyphicon-ok"></span>						
								</button>
								<button class="btn btn-danger btn-sm removeAll marginLeft10" title="{vtranslate('LBL_DESELECT_ALL',$QUALIFIED_MODULE)}">
									<span class="glyphicon glyphicon-remove"></span>						
								</button>
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{/strip}
