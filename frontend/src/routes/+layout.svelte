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
    let timeout;
    onMount(async () => {
        const response = await fetch(import.meta.env.VITE_API_HOST + 'api/token', { method: 'POST' });
        const data = await response.json();
        const token = data.access_token;
        localStorage.setItem('token', token);
        let audio = document.getElementById("player");
        audio.volume = 0.2;
        document.addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                handleSubmit();
            }, 300);
        });
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
<div class="container mx-auto mt-10">
    <input
        bind:value={query}
       on:input={() => {}}
       class="pl-3 text-white rounded border-[2px] w-[80%] sm:w-[50%] md:w-[30%] h-10 border-sky-900 bg-gray-900 hover:bg-gray-600"
        placeholder="Поиск трека"
       required
       type="text"
    >
</div>
<div class="w-full h-[1px] bg-black mt-10"></div>
<div class="container mx-auto mt-10">
    <slot />
</div>
<div class="container mx-auto">
    <audio loop autoplay class="container mx-auto w-full fixed bottom-0" id="player" controls src={preview_url} type="audio/x-m4a">
    </audio>
</div>
</body>
