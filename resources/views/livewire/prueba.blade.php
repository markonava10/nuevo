<div>
    <h1> Hello desde Toluca la bella!!</h1>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1>{{ config('app.name', 'Laravel') }}</h1>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>id</th>
                            <th>Nombre</th>
                            <th></th>
                            <th>Telefono</th>
                            <th>marca</th>
                            <th colspan="2">&nbsp;</th>
                          </tr>
                        </thead>
                      <tbody>
                       
                        @foreach ($customers as $post)
                          <tr>
                            <td> {{ $post->id }}</td>
                            <td> {{ $post->first_name }}</td>
                            <td> {{ $post->last_name }}</td>
                            <td> {{ $post->phone }}</td>
                            <td> {{ $post->marked }}</td>
                            <td>{{ Auth::user()->name }}</td>
                          </tr> 
                        @endforeach    
                      </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div> 

