<div class="row g-4 mb-5">
  <div class="col-md-6">
    <div class="card p-4 h-100">
      <h5 class="mb-3 text-primary">Parent Details</h5>
      <table class="table table-borderless mb-0">
        <tr>
          <th>Kid's Name:</th>
          <td class="th_second">{{ $customer->kid_name }}</td>
        </tr>
        <tr>
          <th>Father Name:</th>
          <td class="th_second">{{ $customer->father_name }}</td>
        </tr>
        <tr>
          <th>Mother Name:</th>
          <td class="th_second">{{ $customer->mother_name }}</td>
        </tr>
        <tr>
          <th>Email:</th>
          <td class="th_second">{{ $customer->email }}</td>
        </tr>
        <tr>
          <th>Mobile:</th>
          <td class="th_second">{{ $customer->mobile }}</td>
        </tr>
        <tr>
          <th>Whatsapp No:</th>
          <td class="th_second">{{ $customer->whatsapp_number }}</td>
        </tr>
        <tr>
          <th>Address:</th>
          <td class="th_second">{{ $customer->address }}</td>
        </tr>
      </table>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card p-4 h-100">
      <h5 class="mb-3 text-primary">Package Details</h5>
      <table class="table table-borderless mb-0">
        <tr>
          <th>Package:</th>
          <td class="th_second">{{ $customer->package }}</td>
        </tr>
        <tr>
          <th>Package Amount:</th>
          <td class="th_second">{{ $customer->package_amount }}</td>
        </tr>
        <tr>
          <th>Advanced:</th>
          <td class="th_second">{{ $customer->advanced }}</td>
        </tr>
        <tr>
          <th>Balance:</th>
          <td class="th_second">{{ $customer->balance }}</td>
        </tr>
        <tr>
          <th>Due Date:</th>
          <td class="th_second">{{ $customer->due_date }}</td>
        </tr>
        <tr>
          <th>Remark:</th>
          <td class="th_second">{{ $customer->remark }}</td>
        </tr>
      </table>
    </div>
  </div>
</div>