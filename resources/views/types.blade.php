<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('Truck Types') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('types.store') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="type" value="truck">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="active" id="active" class="form-check-input" value="1" checked>
                            <label for="active" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Add Truck Type') }}</button>
                    </form>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Active') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($truckTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->description }}</td>
                                    <td>{{ $type->active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="document.getElementById('edit-truck-{{ $type->id }}').style.display='block'">{{ __('Edit') }}</button>
                                        <form action="{{ route('types.destroy', $type->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="type" value="truck">
                                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="edit-truck-{{ $type->id }}" style="display:none;">
                                    <td colspan="4">
                                        <form action="{{ route('types.update', $type->id) }}" method="POST" class="mt-3">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="truck">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $type->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                                <textarea name="description" id="description" class="form-control">{{ $type->description }}</textarea>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ $type->active ? 'checked' : '' }}>
                                                <label for="active" class="form-check-label">{{ __('Active') }}</label>
                                            </div>
                                            <button type="submit" class="btn btn-success">{{ __('Update Truck Type') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header">
                    <h3>{{ __('Cargo Types') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('types.store') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="type" value="cargo">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="active" id="active" class="form-check-input" value="1" checked>
                            <label for="active" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Add Cargo Type') }}</button>
                    </form>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Active') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cargoTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->description }}</td>
                                    <td>{{ $type->active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="document.getElementById('edit-cargo-{{ $type->id }}').style.display='block'">{{ __('Edit') }}</button>
                                        <form action="{{ route('types.destroy', $type->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="type" value="cargo">
                                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="edit-cargo-{{ $type->id }}" style="display:none;">
                                    <td colspan="4">
                                        <form action="{{ route('types.update', $type->id) }}" method="POST" class="mt-3">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="cargo">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $type->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                                <textarea name="description" id="description" class="form-control">{{ $type->description }}</textarea>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ $type->active ? 'checked' : '' }}>
                                                <label for="active" class="form-check-label">{{ __('Active') }}</label>
                                            </div>
                                            <button type="submit" class="btn btn-success">{{ __('Update Cargo Type') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
