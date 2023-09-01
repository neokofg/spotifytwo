<script>
    import {previewUrl, promise} from '../scripts/store.js';

    let items = [];
    let loading = true;

    promise.subscribe(value => {
        items = value;
        if(items != null){
            loading = false;
        }
    });
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms))
    }
    async function playPreview(item, element) {
        let svg = document.getElementById(item.id);
        svg.src = 'loading.gif';
        element.classList.add('zoom');
        await sleep(500);
        element.classList.remove('zoom');
        const track = item.external_urls.spotify;
        const response = await fetch('https://spotify-scraper.p.rapidapi.com/v1/track/download?track=' + track + '&candidate=1', {
            method: 'GET',
            headers: {
                'X-RapidAPI-Key': 'a5e57f7576msh4ec99a648ae4708p1960cajsnf17ea176f616',
                'X-RapidAPI-Host': 'spotify-scraper.p.rapidapi.com'
            }
        });
        const url = await response.json();
        svg.src = 'play.svg';
        previewUrl.set(url.youtubeVideo.audio[0].url);
    }
</script>

{#if !loading}
    <div class="grid grid-cols-1 md:grid-cols-5 sm:grid-cols-3  gap-3">
        {#each items as item}
            <div class="border-[2px] border-sky-900 bg-gray-900 w-full h-full rounded border-black mt-2">
                <div on:click={() => playPreview(item, event.currentTarget)} class="relative hover:opacity-40 transition-all hover:color-white">
                    <img width="60px" height="60px" class="absolute" id={item.id} style="top: calc(50% - 30px); left: calc(50% - 30px)" src={'play.svg'} alt="play">
                    <img src="{item.album.images[0].url}" alt="{item.name}">
                </div>
                <div class="ml-2 mt-2">
                    <h3 class="text-[20px] font-bold hover:opacity-40 transition-all">{item.name}</h3>
                    <p><span class="text-slate-500">Популярность: </span> {item.popularity}</p>
                    <p><span class="text-slate-500">Длительность: </span>{Math.floor(item.duration_ms / 60000)} минуты</p>
                    <p><span class="text-slate-500">Автор: </span> {item.artists[0].name}</p>
                    <p><span class="text-slate-500">Релиз: </span> {item.album.release_date}</p>
                </div>
            </div>
        {/each}
    </div>
{/if}
{#if loading}
    <!-- Show loader while loading -->
    <p>Пишите</p>
{/if}
<div class="w-full h-[100px]">

</div>