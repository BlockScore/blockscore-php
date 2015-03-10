<?php

namespace BlockScore;

class WatchlistTest extends TestCase
{
  public function testUrl()
  {
    $this->assertSame(Watchlist::classUrl(), '/watchlists');
  }

  public function testWatchlistTestSearch()
  {
    $candidate = self::createTestCandidate();
    $wl = Watchlist::search($candidate->id);
    $this->assertSame(0, count($wl->matches));
  }

  public function testWatchlistJohnSearch()
  {
    $candidate = self::createTestJohnCandidate();
    $wl = Watchlist::search($candidate->id);
    $this->assertGreaterThan(0, count($wl->matches));
    $this->assertSame('john bredenkamp', $wl->matches[0]->name_full);
    $this->assertSame('name', $wl->matches[0]->matching_info[0]);
  }
}