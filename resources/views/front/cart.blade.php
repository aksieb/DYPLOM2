@extends('app')

@section('stylesheets')
    <link rel='stylesheet' href={{ asset('/assets/css/shop-item.css') }} />
@endsection

@section('content')
    @include('front.components.navigation')

    <div class="container">
        <div class="row">
            @include('front.components.menu')
            @php
                $fullprice = 0;
            @endphp

            <div class="col-lg-9 mt-5">
                <section>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card wish-list mb-3">
                                <div class="card-body">
                                    <h5 class="mb-4">Koszyk (<span>{{ $count }}</span> przedmiotów)</h5>

                                    @forelse($products as $product)
                                        @php
                                            $cost = $cart[$product->id] * $product->price();
                                            $fullprice += $cost;
                                        @endphp

                                        <div class="row mb-4">
                                            <div class="col-md-5 col-lg-3 col-xl-3">
                                                <div id="carouselControls" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach($product->files as $key => $file)
                                                            <div class="carousel-item active">
                                                                <img class="d-block w-100" src={{ url($file->filename) }} alt="Zdjęcie produktu">
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Poprzedni</span>
                                                    </a>

                                                    <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Następny</span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-7 col-lg-9 col-xl-9">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h5><a href={{ url('/product/' . $product->id) }}>{{ $product->name }}</a></h5>
                                                        <p class="mb-3 text-muted text-uppercase small">Kategoria: {{ $product->category->name }}</p>

                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-borderless mb-0">
                                                                <tbody>
                                                                    @foreach($product->attributes as $attribute)
                                                                        @if($attribute->key != 'price')
                                                                            <tr>
                                                                                <th class="pl-0 w-25" scope="row"><strong>{{ $attribute->name }}</strong></th>
                                                                                <td>{{ $attribute->pivot->value }} {{ $units[$attribute->unit] }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <form method='POST' action={{ url('addToCart') }}>
                                                        @csrf

                                                        <input type='hidden' name='product_id' value={{ $product->id }} />

                                                        <div>
                                                            <div class="def-number-input number-input safari_only mb-0 w-100 d-flex">
                                                                <button
                                                                    type="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()"
                                                                    class="minus"
                                                                >-</button>

                                                                <input class="quantity" min="0" name="quantity" value={{ $cart[$product->id] }} type="number">

                                                                <button
                                                                    type="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                                                                    class="plus"
                                                                >+</button>
                                                            </div>
                                                        </div>

                                                        <div class='row mt-3'>
                                                            <div class='col-sm-12 d-flex justify-content-end'>
                                                                <button type='submit' class='btn btn-primary'>Aktualizuj produkt</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <a
                                                            href={{ url('/deleteFromCart/' . $product->id) }}
                                                            type="button"
                                                            class="card-link-secondary small text-uppercase mr-3">
                                                            <i class="fas fa-trash-alt mr-1"></i> Usuń produkt
                                                        </a>
                                                    </div>

                                                    <p class="mb-0"><span><strong>{{ $cart[$product->id] }}x{{ $product->price() }}zł = {{ $cost }} zł</strong></span></p>
                                                </div>

                                                <hr class="mb-4">
                                            </div>
                                        </div>
                                    @empty
                                        <div class="row mb-4">
                                            <div class="col-sm-12">
                                                <div class="d-flex justify-content-between">
                                                    Brak przedmiotów w koszyku
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse

                                    <p class="text-primary mb-4">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Dodanie produktu do koszyka nie powoduje rezerwacji produktu.
                                    </p>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="mb-4">Oczekiwany czas dostawy</h5>

                                    <p class="mb-0"> Do 10 dni roboczych</p>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">

                                    <h5 class="mb-4">Akceptujemy płatności</h5>

                                    <img class="mr-2" width="45px"
                                        src="https://mdbootstrap.com/wp-content/plugins/woocommerce-gateway-stripe/assets/images/visa.svg"
                                        alt="Visa">
                                    <img class="mr-2" width="45px"
                                        src="https://mdbootstrap.com/wp-content/plugins/woocommerce-gateway-stripe/assets/images/amex.svg"
                                        alt="American Express">
                                    <img class="mr-2" width="45px"
                                        src="https://mdbootstrap.com/wp-content/plugins/woocommerce-gateway-stripe/assets/images/mastercard.svg"
                                        alt="Mastercard">

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <div class="card-body">

                                    <h5 class="mb-3">Podsumowanie</h5>

                                    <ul class="list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                            Cena produktów
                                            <span>{{ $fullprice }} zł</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            Dostawa
                                            <span>Gratis</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                            <div>
                                                <strong>Cena zamówienia</strong>

                                            </div>
                                            <span><strong>{{ $fullprice }} zł</strong></span>
                                        </li>
                                    </ul>

                                    <a
                                        href={{ url('/order') }}
                                        class="btn btn-primary btn-block waves-effect waves-light"
                                    >Zamawiam</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
