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

    async function playPreview(spotify_url) {
        const track = spotify_url
        const response = await fetch('https://spotify-scraper.p.rapidapi.com/v1/track/download?track=' + track + '&candidate=1', {
            method: 'GET',
            headers: {
                'X-RapidAPI-Key': 'a5e57f7576msh4ec99a648ae4708p1960cajsnf17ea176f616',
                'X-RapidAPI-Host': 'spotify-scraper.p.rapidapi.com'
            }
        });
        const url = await response.json();
        console.log(url);
        previewUrl.set(url.youtubeVideo.audio[0].url);
    }
</script>

{#if !loading}
    <div class="cards mt-20">
        {#each items as item}
            <div on:click={() => playPreview(item.external_urls.spotify)} class="border-[1px] rounded border-black w-[120px] mt-2">
                <h3>{item.name}</h3>
                <p>{item.artists[0].name}</p>
                <!-- other item properties -->
            </div>
        {/each}
    </div>
{/if}
{#if loading}
    <!-- Show loader while loading -->
    <p>Loading...</p>
{/if}
