<?php

namespace App;

class Template extends Controller {

	function get() {
		$f3=\Base::instance();
		$test=new \Test;
		$test->expect(
			is_null($f3->get('ERROR')),
			'No errors expected at this point'
		);
		$tpl=\Template::instance();
		$f3->set('foo','bar->baz');
		$test->expect(
			$tpl->serve('templates/test1.htm')=='bar-&gt;baz',
			'Auto-escaping enabled'
		);
		$test->expect(
			$tpl->token($expr='@foo.bar')==($eval='$foo[\'bar\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo[bar]')==($eval='$foo[\'bar\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo[\'bar\']')==($eval='$foo[\'bar\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo["bar"]')==($eval='$foo["bar"]'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo.@bar')==($eval='$foo.$bar'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo[@bar]')==($eval='$foo[$bar]'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->bar.baz')==($eval='$foo->bar[\'baz\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->bar[baz]')==($eval='$foo->bar[\'baz\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->bar.@baz')==($eval='$foo->bar.$baz'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->bar[@baz]')==($eval='$foo->bar[$baz]'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->@baz')==($eval='$foo->$baz'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo::bar.baz')==($eval='$foo::bar[\'baz\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo::bar[baz]')==($eval='$foo::bar[\'baz\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo::bar.@baz')==($eval='$foo::bar.$baz'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo::@baz')==($eval='$foo::$baz'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo::bar[@baz]')==($eval='$foo::bar[$baz]'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->bar[@qux.baz]')==($eval='$foo->bar[$qux[\'baz\']]'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->bar[@qux.@baz]')==($eval='$foo->bar[$qux.$baz]'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo()')==($eval='$foo()'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo()->bar')==($eval='$foo()->bar'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo.zip()')==($eval='$foo[\'zip\']()'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo.zip(@bar)')==($eval='$foo[\'zip\']($bar)'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo.zip(@bar,@baz)')==($eval='$foo[\'zip\']($bar,$baz)'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo.zip(@bar,\'qux\')')==($eval='$foo[\'zip\']($bar,\'qux\')'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo->zip(@bar,\'qux\')')==($eval='$foo->zip($bar,\'qux\')'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo[google.com]')==($eval='$foo[\'google.com\']'),
			$expr.': '.$eval
		);
		$test->expect(
			$tpl->token($expr='@foo.\'hello, world\'')==($eval='$foo.\'hello, world\''),
			$expr.': '.$eval
		);
		$f3->set('foo','bar');
		$f3->set('cond',TRUE);
		$f3->set('file','templates/test1.htm');
		$test->expect(
			$tpl->serve('templates/test2.htm')=='bar',
			'<include>'
		);
		$f3->clear('cond');
		$f3->set('foo','baz');
		$test->expect(
			$tpl->serve('templates/test3.htm')=='baz',
			'<exclude> and {{* comment *}}'
		);
		$f3->clear('foo');
		$f3->set('div',
			array(
				'coffee'=>array('arabica','barako','liberica','kopiluwak'),
				'tea'=>array('darjeeling','pekoe','samovar')
			)
		);
		$test->expect(
			preg_replace('/[\t\n]/','',
				$tpl->serve('templates/test4.htm'))==
				'<div>'.
					'<p><span><b>coffee</b></span></p>'.
					'<p>'.
						'<span>arabica</span>'.
						'<span>barako</span>'.
						'<span>liberica</span>'.
						'<span>kopiluwak</span>'.
					'</p>'.
				'</div>'.
				'<div>'.
					'<p><span><b>tea</b></span></p>'.
					'<p>'.
						'<span>darjeeling</span>'.
						'<span>pekoe</span>'.
						'<span>samovar</span>'.
					'</p>'.
				'</div>',
			'<repeat>'
		);
		$f3->clear('div');
		$f3->set('cond1',TRUE);
		$f3->set('cond2',TRUE);
		$test->expect(
			trim($tpl->serve('templates/test5.htm'))==
				'c1:T,c2:T',
			'<check>, <true>, <true>'
		);
		$f3->set('cond1',TRUE);
		$f3->set('cond2',FALSE);
		$test->expect(
			trim($tpl->serve('templates/test5.htm'))==
				'c1:T,c2:F',
			'<check>, <true>, <false>'
		);
		$f3->set('cond1',FALSE);
		$f3->set('cond2',TRUE);
		$test->expect(
			trim($tpl->serve('templates/test5.htm'))==
				'c1:F,c2:T',
			'<check>, <false>, <true>'
		);
		$f3->set('cond1',FALSE);
		$f3->set('cond2',FALSE);
		$test->expect(
			trim($tpl->serve('templates/test5.htm'))==
				'c1:F,c2:F',
			'<check>, <false>, <false>'
		);
		$f3->clear('cond1');
		$f3->clear('cond2');
		$test->expect(
			preg_replace('/[\t\n]/','',
				$tpl->serve('templates/test6.htm'))==
					'<div>'.
						'<p class="odd">1</p>'.
						'<p class="even">2</p>'.
						'<p class="odd">3</p>'.
					'</div>'.
					'Temporary variable preserved across includes',
			'<loop> with embedded <include>'
		);
		$test->expect(
			preg_replace('/[\t\n]/','',
				$tpl->serve('templates/test8.htm'))==
					'<span>3</span>'.
					'<span>6</span>'.
					'<span>xyz</span>'.
					'<span>array(1,3,5)</span>'.
					'<span>a</span>'.
					'<span>b</span>'.
					'<span>c</span>'.
					'email@address.com',
			'<set>'
		);
		$f3->set('results',$test->results());
	}

}
