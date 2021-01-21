<div class="col-lg-3">
    <h1 class="my-4">Spożywczak
    </h1>
    <div class="list-group">
        @foreach ($categories as $key => $category)
            <p>
                <a class="btn btn-link" data-toggle="collapse" href={{ '#category-' . $key }} role="button"
                    aria-expanded="false" aria-controls={{ 'category-' . $key }}>
                    {{ $category->name }}
                </a>
            </p>
            <div class="collapse" id={{ 'category-' . $key }}>
                <ul>
                    @foreach ($category->categories as $subcategory)
                        <li><a href="{{ url('/category/' . $subcategory->id) }}">{{ $subcategory->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
