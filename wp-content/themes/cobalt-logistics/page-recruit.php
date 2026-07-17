<?php
/**
 * Template Name: 採用情報
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main>

	<section class="page-hero">
		<div class="container">
			<h1 class="page-hero__title">採用情報</h1>
			<p class="page-hero__lead">コバルト物流は、EC事業者の"物流の裏側"を支えるチームです。現場スタッフから管理職まで、幅広く仲間を募集しています。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<h2 class="section-title">募集職種</h2>
			<div class="job-list">
				<div class="job-card">
					<div>
						<p class="job-card__title">倉庫内オペレーションスタッフ</p>
						<p class="job-card__meta">横浜本社倉庫</p>
					</div>
					<span class="job-card__badge">正社員/契約社員</span>
				</div>
				<div class="job-card">
					<div>
						<p class="job-card__title">物流管理職</p>
						<p class="job-card__meta">横浜本社</p>
					</div>
					<span class="job-card__badge">正社員</span>
				</div>
				<div class="job-card">
					<div>
						<p class="job-card__title">配送コーディネーター</p>
						<p class="job-card__meta">横浜本社</p>
					</div>
					<span class="job-card__badge">正社員</span>
				</div>
			</div>
		</div>
	</section>

	<section class="section section--alt">
		<div class="container">
			<h2 class="section-title">働く環境</h2>
			<div class="perks-grid">
				<div class="perk-card">
					<div class="stat-icon" style="margin: 0 auto 12px;"><?php cobalt_logistics_icon( 'check' ); ?></div>
					<p class="perk-card__title">未経験入社スタッフが多数活躍中</p>
					<p class="perk-card__text">未経験からでも安心して始められる教育体制を整えています。</p>
				</div>
				<div class="perk-card">
					<div class="stat-icon" style="margin: 0 auto 12px;"><?php cobalt_logistics_icon( 'check' ); ?></div>
					<p class="perk-card__title">資格取得支援制度あり</p>
					<p class="perk-card__text">業務に関連する資格取得を会社がサポートします。</p>
				</div>
				<div class="perk-card">
					<div class="stat-icon" style="margin: 0 auto 12px;"><?php cobalt_logistics_icon( 'check' ); ?></div>
					<p class="perk-card__title">年間休日115日</p>
					<p class="perk-card__text">プライベートとの両立がしやすい休日設定です。</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<h2 class="section-title">ご応募方法</h2>
			<p class="section-lead">下記お問い合わせフォームより「採用について」を選択の上ご連絡ください。</p>

			<form class="apply-form" onsubmit="return false;">
				<div class="apply-form__field">
					<label for="applicant-name">お名前</label>
					<input type="text" id="applicant-name" name="applicant-name" placeholder="山田 太郎">
				</div>
				<div class="apply-form__field">
					<label for="applicant-email">メールアドレス</label>
					<input type="email" id="applicant-email" name="applicant-email" placeholder="example@example.com">
				</div>
				<div class="apply-form__field">
					<label for="applicant-subject">お問い合わせ内容</label>
					<select id="applicant-subject" name="applicant-subject">
						<option>採用について</option>
						<option>資料請求について</option>
						<option>倉庫見学について</option>
						<option>その他</option>
					</select>
				</div>
				<div class="apply-form__field">
					<label for="applicant-message">メッセージ</label>
					<textarea id="applicant-message" name="applicant-message" rows="4" placeholder="ご希望の職種などをご記入ください"></textarea>
				</div>
				<button type="submit" class="btn btn--solid" style="width:100%;">送信する</button>
				<p class="apply-form__note">※ 本サイトはポートフォリオ用デモのため、実際の送信機能はありません。</p>
			</form>
		</div>
	</section>

</main>

<?php
get_footer();
