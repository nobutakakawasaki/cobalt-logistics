<?php
/**
 * Template Name: FAQ
 *
 * @package Cobalt_Logistics
 */

get_header();

$cobalt_faqs = array(
	array(
		'q' => '小ロットでも依頼できますか？',
		'a' => 'はい。月間出荷数百件程度からご利用いただけます。',
	),
	array(
		'q' => '契約期間の縛りはありますか？',
		'a' => '最低契約期間は3ヶ月からとなります。詳細はお問い合わせください。',
	),
	array(
		'q' => '対応可能な配送エリアは？',
		'a' => '全国対応可能です。離島・一部地域は別途ご相談ください。',
	),
	array(
		'q' => 'システム連携は可能ですか？',
		'a' => '主要なECカート・モールとのAPI連携に対応しています。',
	),
	array(
		'q' => '見積もりまでの流れは？',
		'a' => 'お問い合わせ後、ヒアリングを経て概算お見積りを2営業日以内にご提示します。',
	),
	array(
		'q' => '冷凍・冷蔵品の取り扱いは可能ですか？',
		'a' => '現状は常温・チルド（冷蔵）帯のみ対応しております。冷凍品につきましては個別にご相談ください。',
	),
	array(
		'q' => '出荷後にトラブルが発生した場合の対応は？',
		'a' => '誤出荷・破損等が発生した場合は、当社規定に基づき状況確認のうえ速やかに対応いたします。再発防止策もあわせてご報告します。',
	),
	array(
		'q' => '導入までにかかる期間はどれくらいですか？',
		'a' => 'お打ち合わせ・在庫移管の準備期間を含め、稼働開始まで通常3〜4週間程度を目安としています。繁忙期を避けたい場合はお早めにご相談ください。',
	),
	array(
		'q' => '繁忙期のみの一時的な増員対応は可能ですか？',
		'a' => 'はい。セール時期や季節波動に合わせた一時的な人員・スペースの増強に対応しております。事前にご相談いただけますとスムーズです。',
	),
	array(
		'q' => '既存の在庫を移管する場合、どのような流れになりますか？',
		'a' => '現状の在庫データをお預かりし、搬入・棚卸を実施のうえシステムへ反映します。移管中の出荷継続方法についても個別にご案内いたします。',
	),
);
?>

<main id="main">

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">FAQ</p>
			<h1 class="page-hero__title">よくあるご質問</h1>
			<p class="page-hero__lead">お問い合わせの多いご質問をまとめました。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="faq-list">
				<?php foreach ( $cobalt_faqs as $cobalt_index => $cobalt_faq ) : ?>
					<div class="faq-item">
						<button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr( $cobalt_index ); ?>">
							<span><?php echo esc_html( $cobalt_faq['q'] ); ?></span>
							<span class="faq-question__icon" aria-hidden="true"></span>
						</button>
						<div class="faq-answer" id="faq-answer-<?php echo esc_attr( $cobalt_index ); ?>">
							<div class="faq-answer__inner">
								<p><?php echo esc_html( $cobalt_faq['a'] ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
