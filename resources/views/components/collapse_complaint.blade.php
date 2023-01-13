<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading-{{ $complaint->id }}">
            <button class="accordion-button @if(!($complaints->count() == $i)) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $complaint->id }}"
                aria-expanded="{{ ($complaints->count() == $i) ? 'true' : 'false' }}" aria-controls="#collapse-{{ $complaint->id }}">
                Complaint #{{ $complaint->id }}
            </button>
        </h2>
        <div id="collapse-{{ $complaint->id }}" class="accordion-collapse collapse @if($complaints->count() == $i) show @endif" aria-labelledby="heading-{{ $complaint->id }}"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">

                @include('components.collapse_complaint_form_content')

            </div>
        </div>
    </div>
</div>
