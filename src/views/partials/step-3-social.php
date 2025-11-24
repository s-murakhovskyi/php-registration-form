<div class="text-center mt-5">
    <h3 class="text-success">Registration Complete!</h3>
    <p class="lead">Share this meetup with friends:</p>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <!-- Facebook Share -->
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL); ?>"
           target="_blank" class="btn btn-primary">
            Share on Facebook
        </a>

        <!-- Twitter Share -->
        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(TWITTER_SHARE_TEXT); ?>&url=<?php echo urlencode(BASE_URL); ?>"
           target="_blank" class="btn btn-info text-white">
            Share on Twitter
        </a>
    </div>

    <hr class="my-5">

    <!-- Link to All Members -->
    <a href="/all-members" class="btn btn-success btn-lg">View All Members</a>
</div>