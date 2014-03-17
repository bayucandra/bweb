	{$error_messages}
	<br /><span class="ftimes f 13" style="margin-top:10px;">Please fill form below in order to receive copy of our catalog. The fields with asterisk marks <span class="fred">(*)</span> are required to be filled.</span>
	<form action="{$php_self}" method="post">
		<table class="farial f14" cellpadding="5">
			<tr>
				<td colspan="3" class="header fbold ftahoma f12 funderline">Catalog form request</td>
			</tr>
			<tr>
				<td class="label">Full Name<span class="fred">*</span></td>
				<td>:</td>
				<td>
					<select name="title">
						<option value="Mr."{$val.title_mr}>Mr.</option>
						<option value="Mrs."{$val.title_mrs}>Mrs.</option>
					</select>
					<input class="inp_text{$errors.fullname}" value="{$val.fullname}" type="text" name="fullname" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="label">Address<span class="fred">*</span></td>
				<td>:</td>
				<td><textarea class="inp_text{$errors.address}" type="text" name="address" maxlength="255">{$val.address}</textarea></td>
			</tr>
			<tr>
				<td class="label">Country<span class="fred">*</span></td>
				<td>:</td>
				<td><input class="inp_text{$errors.country}" value="{$val.country}" type="text" name="country" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Phone<span class="fred">*</span></td>
				<td>:</td>
				<td><input class="inp_text{$errors.phone}" value="{$val.phone}" type="text" name="phone" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Fax</td>
				<td>:</td>
				<td><input class="inp_text" type="text" value="{$val.fax}" name="fax" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Email Address<span class="fred">*</span></td>
				<td>:</td>
				<td><input class="inp_text{$errors.email}" value="{$val.email}" type="text" name="email" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Website</td>
				<td>:</td>
				<td><input class="inp_text" value="{$val.website}" type="text" name="website" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label"></td>
				<td></td>
				<td>
					<input type="submit" name="submit_catalog" value="Submit Request" />
					&nbsp;<input type="reset" value="Reset"/>
				</td>
			</tr>
		</table>
	</form>