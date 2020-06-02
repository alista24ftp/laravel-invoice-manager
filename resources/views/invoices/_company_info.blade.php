<div class="card">
  <h5 class="card-header">Company Info</h5>
  <div class="card-body">
    <div class="form-group">
      <label for="company_name">Company Name</label>
      <input type="text" name="company_name" id="company_name" class="form-control"
        value="{{old('company_name', ($invoice->company_name ?? $company->company_name))}}" />
    </div>
    <div class="form-row">
      <div class="form-group col-9">
        <label for="company_mail_addr">Mailing Address</label>
        <input type="text" id="company_mail_addr" name="company_mail_addr" class="form-control"
          value="{{old('company_mail_addr', ($invoice->company_mail_addr ?? $company->mail_addr))}}" />
      </div>
      <div class="form-group col-3">
        <label for="company_mail_postal">Postal Code</label>
        <input type="text" name="company_mail_postal" id="company_mail_postal"
          class="form-control" maxlength="6"
          value="{{old('company_mail_postal', ($invoice->company_mail_postal ?? $company->mail_postal))}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-9">
        <label for="company_ware_addr">Warehouse Address</label>
        <input type="text" id="company_ware_addr" name="company_ware_addr" class="form-control"
          value="{{old('company_ware_addr', ($invoice->company_ware_addr ?? $company->warehouse_addr))}}" />
      </div>
      <div class="form-group col-3">
        <label for="company_ware_postal">Postal Code</label>
        <input type="text" name="company_ware_postal" id="company_ware_postal"
          class="form-control" maxlength="6"
          value="{{old('company_ware_postal', ($invoice->company_ware_postal ?? $company->warehouse_postal))}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-6">
        <label for="company_email">Company Email</label>
        <input type="text" class="form-control" id="company_email" name="company_email"
          value="{{old('company_email', ($invoice->company_email ?? $company->email))}}" />
      </div>
      <div class="form-group col-6">
        <label for="company_website">Company Website</label>
        <input type="text" class="form-control" id="company_website" name="company_website"
          value="{{old('company_website', ($invoice->company_name ? $invoice->company_website : $company->website))}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-4">
        <label for="company_tel">Company Tel</label>
        <input type="text" name="company_tel" id="company_tel"
          class="form-control" maxlength="11" value="{{old('company_tel', ($invoice->company_tel ?? $company->tel))}}" />
      </div>
      <div class="form-group col-4">
        <label for="company_fax">Company Fax</label>
        <input type="text" name="company_fax" id="company_fax"
          class="form-control" maxlength="11"
          value="{{old('company_fax', ($invoice->company_tel ? $invoice->company_fax : $company->fax))}}" />
      </div>
      <div class="form-group col-4">
        <label for="company_tollfree">Company Toll-Free</label>
        <input type="text" name="company_tollfree" id="company_tollfree"
          class="form-control" maxlength="11"
          value="{{old('company_tollfree', ($invoice->company_tel ? $invoice->company_tollfree : $company->toll_free))}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-3">
        <label for="company_contact_fname">Contact First Name</label>
        <input type="text" id="company_contact_fname" name="company_contact_fname"
          class="form-control" maxlength="30"
          value="{{old('company_contact_fname', ($invoice->company_contact_fname ?? $company->contact1_firstname))}}" />
      </div>
      <div class="form-group col-3">
        <label for="company_contact_lname">Contact Last Name</label>
        <input type="text" id="company_contact_lname" name="company_contact_lname"
          class="form-control" maxlength="30"
          value="{{old('company_contact_lname', ($invoice->company_contact_fname ? $invoice->company_contact_lname : $company->contact1_lastname))}}" />
      </div>
      <div class="form-group col-6">
        <label for="company_contact_email">Contact Email</label>
        <input type="text" id="company_contact_email" name="company_contact_email"
          class="form-control" maxlength="30"
          value="{{old('company_contact_email', ($invoice->company_contact_fname ? $invoice->company_contact_email : $company->contact1_email))}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-4">
        <label for="company_contact_tel">Contact Tel</label>
        <input type="text" id="company_contact_tel" name="company_contact_tel"
          class="form-control" maxlength="11"
          value="{{old('company_contact_tel', ($invoice->company_contact_tel ?? $company->contact1_tel))}}" />
      </div>
      <div class="form-group col-4">
        <label for="company_contact_cell">Contact Cell</label>
        <input type="text" id="company_contact_cell" name="company_contact_cell"
          class="form-control" maxlength="11"
          value="{{old('company_contact_cell', ($invoice->company_contact_tel ? $invoice->company_contact_cell : $company->contact1_cell))}}" />
      </div>
    </div>
  </div>
</div>
