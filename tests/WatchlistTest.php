<?php

namespace BlockScore;

class WatchlistTest extends TestCase
{
    public function testUrl()
    {
        $this->assertSame(Watchlist::classUrl(), '/watchlists');
    }

    public function testClassType()
    {
        $candidate = self::createTestCandidate();
        $wl = $candidate->watchlists->search();
        $this->assertTrue($wl instanceof Watchlist);
        $this->assertTrue($wl instanceof BaseObject);
    }
    
    public function testWatchlistTestSearch()
    {
        $candidate = self::createTestCandidate();
        $wl = $candidate->watchlists->search();
        $this->assertSame(0, count($wl->matches));
    }
    
    public function testWatchlistJohnSearch()
    {
        $candidate = self::createTestJohnCandidate();
        $wl = $candidate->watchlists->search();
        $this->assertGreaterThan(0, count($wl->matches));
        $this->assertSame('John Bredenkamp', $wl->matches[0]->name_full);
        $this->assertSame('name', $wl->matches[0]->matching_info[0]);
    }
}