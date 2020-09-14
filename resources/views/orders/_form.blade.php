    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Title</label>
          <input type="text" name="title" class="form-control  @error('title') is-invalid @enderror" value="{{ old('title', $order->title) }}">  
        </div>
        <div class="form-group">
          <label>Cost</label>
          <input type="cost" name="cost" class="form-control  @error('cost') is-invalid @enderror" value="{{ old('cost', $order->cost) }}">
        </div>
      </div>
      <div class="col-md-6">
        <label>Description</label>
        <textarea name="description" class="form-control  @error('description') is-invalid @enderror" rows="4" style="resize: none;">{{ old('description', $order->description) }}</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Customer</label>
          <select name="customer_id" class="form-control  @error('customer_id') is-invalid @enderror">
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
          <select name="tag_id[]" class="form-control  @error('tag_id') is-invalid @enderror" multiple="true">
            <option value="">Select one or more tags</option>
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}" @if(in_array($tag->id, old('tag_id', $order->tags->pluck('id')->toArray()))) selected="selected" @endif>{{ $tag->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
