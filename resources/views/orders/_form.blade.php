<div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Title</label>
          <input type="text" name="title" class="form-control" value="{{ old('title', $order->title) }}">  
        </div>
        <div class="form-group">
          <label>Cost</label>
          <input type="cost" name="cost" class="form-control" value="{{ old('cost', $order->cost) }}">
        </div>
      </div>
      <div class="col-md-6">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" style="resize: none;">{{ old('description', $order->description) }}</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Customer</label>
          <select name="customer_id" class="form-control">
            <option value="">Select a customer</option>
            @foreach($customers as $customer)
              <option value="{{ $customer->id }}" @if(old('customer_id', $order->customer_id) == $customer->id) selected="selected" @endif>{{ $customer->last_name }} {{ $customer->first_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Tags</label>
          <select name="tag_id[]" class="form-control" multiple="true">
            <option value="">Select one or more tags</option>
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}" @if(in_array($customer->id, old('tag_id', $order->tags->pluck('id')->toArray()))) selected="selected" @endif>{{ $tag->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
