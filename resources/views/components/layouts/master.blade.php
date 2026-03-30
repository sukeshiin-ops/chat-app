<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


    <style>
        html,
        body {
            height: 100%;

        }

        /* Sidebar Scroll */
        ul::-webkit-scrollbar {
            width: 5px;
        }

        ul::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        /* Hover effect */
        li:hover {
            background: #f5f5f5;
            cursor: pointer;
        }

        div::-webkit-scrollbar {
            width: 5px;
        }

        div::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 10px;
        }

        .navbar {
            background: #020617 !important;
            border-bottom: 1px solid #1e293b;
        }

        .dropdown-menu {
            background: #020617;
            border: 1px solid #1e293b;
        }

        .dropdown-item {
            color: #e2e8f0;
        }

        .dropdown-item:hover {
            background: #1e293b;
            color: #fff;
        }

        /* Search bar hover effect */
        .input-group input:focus {
            outline: none;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.15);
            background-color: #f7f7f7;
        }

        /* Smooth icon alignment */
        .input-group-text i {
            font-size: 1rem;
        }

        /* Optional: sticky shadow effect */
        .sticky-top {
            z-index: 10;
        }
    </style>
</head>

<body>




    @include('components.layouts.navbar')

    <section>
        <div class="container-fluid" style="height: calc(100vh - 56px);">

            <div class="row h-100">

                <div class="col-md-4 col-lg-3 p-0 d-flex flex-column border-end">
                    <div class="card">
                        <div class="card-body p-0">

                            <!-- SEARCH BAR -->
                            <div class="p-3 border-bottom sticky-top bg-white shadow-sm">
                                <div class="input-group rounded-pill overflow-hidden">
                                    <input type="text" class="form-control border-0 bg-secondary"
                                        placeholder="Search users...">
                                </div>
                            </div>


                            {{-- Side Bar --}}
                            @include('components.layouts.sidebar')


                        </div>
                    </div>

                </div>

                <div class="col-md-8 col-lg-9 p-0 d-flex flex-column" style="border: solid 8px rgb(255, 255, 255)">


                    <!-- Chat Header -->
                    <div class="p-3 border-bottom bg-white">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-8.webp" class="rounded-circle me-3"
                            width="50">
                        <strong>Brad Pitt</strong>
                    </div>


                    <!-- Chat Body (SCROLL) -->
                    @include('components.layouts.body')

                    <!-- Chat Footer -->
                    @include('components.layouts.footer')

                </div>

            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

</body>

</html>
