@if(!empty($success) || session('success'))
    <div class="columns">
        <div class="column is-centered is-6">
            <article class="message is-success">
                <div class="message-header">Good news everyone!</div>
                <div class="message-body">{{ !empty($success) ? $success : session('success') }}</div>
            </article>
        </div>
    </div>
@endif

@if(!empty($error) || session('error'))
    <div class="columns">
        <div class="column is-centered is-6">
            <article class="message is-danger">
                <div class="message-header">Whoops, we got a problem!</div>
                <div class="message-body">{{ !empty($error) ? $error : session('error') }}</div>
            </article>
        </div>
    </div>
@endif