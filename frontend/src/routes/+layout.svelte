<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Two</title>
</head>

<script>
    import {previewUrl, promise} from '../scripts/store.js';
    import "../app.css";
    import {onMount} from "svelte";

    onMount(async () => {
        const response = await fetch('http://localhost:8000/api/token', { method: 'POST' });
        const data = await response.json();
        const token = data.access_token;
        localStorage.setItem('token', token);
        let audio = document.getElementById("player");
        audio.volume = 0.2;
    });

    let query = '';
    async function handleSubmit() {
        const token = localStorage.getItem('token');
        const response = await fetch('http://localhost:8000/api/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                query,
                token
            })
        });
        const data = await response.json();
        promise.set(data.tracks.items);
    }
    let preview_url = '';
    previewUrl.subscribe(value => {
        preview_url = value;
    });
</script>

<body>
<div class="container mx-auto">
    <form on:submit={handleSubmit}>
        <input class="rounded border-[1px] border-black" bind:value={query} required type="text" placeholder="search">
        <button type="submit">Search</button>
    </form>
    <slot />
</div>
<audio class="w-full absolute bottom-0" id="player" controls src={preview_url} type="audio/x-m4a">
</audio>
</body>
