 <div class="space-y-4">
   <div>
        <label class="block font-medium">Type</label>
        {{-- <input type="text" name="type" value="{{ old('type', $data->type ?? '') }}"
               placeholder="Expense Type e.g. Food, Travel..." required class="w-full border p-2 rounded"> --}}
              <select name="type" class="w-full border p-2 rounded">
                     @dump($data)
                     @foreach ($data as $expense ){
                            <option value="{{$expense->type}}">{{$expense->type}}</option>
                     }
                     @endforeach
              </select>
    </div>

    <div>
        <label class="block font-medium">Description</label>
        <input type="text" name="description" value="{{ old('description', $data->description ?? '') }}"
               placeholder="Enter Expense Description..." required class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="block font-medium">Amount</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $data->amount ?? '') }}"
               placeholder="Enter amount..." required class="w-full border p-2 rounded">
    </div>

    <div>
        <label class="block font-medium">Expense Date</label>
        <input type="date" name="expense_date" required value="{{ old('expense_date', '') }}"
               class="w-full border p-2 rounded">
    </div>
        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-black">Save</button>

 </div>
