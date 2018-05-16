<button type="button"
        class="btn btn-danger btn-xs"
        data-toggle="modal"
        data-target=".destroy-confirm-modal-{{ $entity->id }}"
        title="{{ trans('tablelist::tablelist.tbody.action.destroy') }}">
    <i class="glyphicon glyphicon-trash"></i>
</button>
