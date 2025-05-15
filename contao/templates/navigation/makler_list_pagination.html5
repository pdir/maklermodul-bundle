<?php if(count($this->articles) > 1): ?>
    <!-- indexer::stop -->
    <div class="pagination block">
        <p><?php echo $this->total; ?></p>
        <ul>
            <?php if ($this->first): ?>
                <li class="first"><a href="<?php echo $this->first['href']; ?>" class="first" title="<?php echo $this->first['title']; ?>"><?php echo $this->first['link']; ?></a></li>
            <?php endif; ?>
            <?php if ($this->previous): ?>
                <li class="previous"><a href="<?php echo $this->previous['href']; ?>" class="previous" title="<?php echo $this->previous['title']; ?>"><?php echo $this->previous['link']; ?></a></li>
            <?php endif; ?>
            <?php if ($this->startat): ?>
                <li class="startat"><a href="<?php echo $this->startat['href']; ?>" class="startat" title="<?php echo $this->startat['title']; ?>"><?php echo $this->startat['link']; ?></a></li>
            <?php endif; ?>
            <?php foreach ($this->articles as $article): ?>
                <?php if ($article['isActive']): ?>
                    <li><span class="current"><?php echo $article['link']; ?></span></li>
                <?php else: ?>
                    <li><a href="<?php echo $article['href']; ?>" class="link" title="<?php echo $article['title']; ?>"><?php echo $article['link']; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($this->stopat): ?>
                <li class="stopat"><a href="<?php echo $this->stopat['href']; ?>" class="stopat" title="<?php echo $this->stopat['title']; ?>"><?php echo $this->stopat['link']; ?></a></li>
            <?php endif; ?>
            <?php if ($this->next): ?>
                <li class="next"><a href="<?php echo $this->next['href']; ?>" class="next" title="<?php echo $this->next['title']; ?>"><?php echo $this->next['link']; ?></a></li>
            <?php endif; ?>
            <?php if ($this->last): ?>
                <li class="last"><a href="<?php echo $this->last['href']; ?>" class="last" title="<?php echo $this->last['title']; ?>"><?php echo $this->last['link']; ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- indexer::continue -->
<?php endif; ?>