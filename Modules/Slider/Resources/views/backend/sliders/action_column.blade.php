<div class="d-flex gap-2 align-items-center">
    @hasPermission('edit_app_banner')
        <button type="button" class="btn btn-primary btn-sm" data-crud-id="{{ $data->id }}"
            title="{{ __('messages.edit') }} " data-bs-toggle="tooltip"> <i class="fa-solid fa-pen-clip"></i></button>
    @endhasPermission
    @hasPermission('delete_app_banner')
        <a href="{{ route('backend.app-banners.destroy', $data->id) }}" id="delete-{{ $module_name }}-{{ $data->id }}"
            class="btn btn-danger btn-sm" data-type="ajax" data-method="DELETE" data-token="{{ csrf_token() }}"
            data-bs-toggle="tooltip" title="{{ __('messages.delete') }}"
            data-confirm="{{ __('messages.are_you_sure?', ['module' => __('slider.singular_title'), 'name' => $data->name]) }}">
            <i class="fa-solid fa-trash"></i></a>
    @endhasPermission
</div>
