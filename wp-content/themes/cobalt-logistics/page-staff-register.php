<?php
/**
 * Template Name: 社員登録
 *
 * Internal staff-registration page (STAFF_AUTH_BRIEF.md). Intentionally not
 * linked from the main navigation or footer — this is a社内向け page, not a
 * public sales page, reachable only by someone who already knows the URL
 * (and, more importantly, the registration code checked server-side in
 * cobalt_logistics_handle_staff_registration() in functions.php).
 *
 * @package Cobalt_Logistics
 */

get_header();

$cobalt_staff_register_status     = isset( $_GET['staff_register'] ) ? sanitize_key( wp_unslash( $_GET['staff_register'] ) ) : '';
$cobalt_staff_register_errors_raw = isset( $_GET['staff_register_errors'] ) ? sanitize_text_field( wp_unslash( $_GET['staff_register_errors'] ) ) : '';

// Per-field error labels. Unlike the staff-login failure message (which is
// intentionally generic to avoid account-enumeration, see
// page-staff-login.php), specific field-level errors here are safe: this
// form is already gated by the registration code, and the messages don't
// reveal whether any *account* exists — only whether a submitted staff ID/
// email is already taken, which is normal, expected registration-form UX.
$cobalt_staff_register_error_labels = array(
	'name'              => 'お名前を入力してください',
	'staff_id_format'   => '社員IDは半角英数字で入力してください',
	'staff_id_taken'    => 'その社員IDは既に使用されています',
	'email_format'      => 'メールアドレスの形式が正しくありません',
	'email_taken'       => 'そのメールアドレスは既に登録されています',
	'password_length'   => 'パスワードは8文字以上で入力してください',
	'password_mismatch' => 'パスワード（確認用）が一致しません',
	'reg_code'          => '登録コードが正しくありません',
	'unknown'           => '登録に失敗しました。時間をおいて再度お試しください',
);

$cobalt_staff_register_error_messages = array();
if ( $cobalt_staff_register_errors_raw ) {
	foreach ( explode( ',', $cobalt_staff_register_errors_raw ) as $cobalt_staff_register_error_key ) {
		if ( isset( $cobalt_staff_register_error_labels[ $cobalt_staff_register_error_key ] ) ) {
			$cobalt_staff_register_error_messages[] = $cobalt_staff_register_error_labels[ $cobalt_staff_register_error_key ];
		}
	}
}
?>

<main id="main">

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">STAFF</p>
			<h1 class="page-hero__title">社員登録</h1>
			<p class="page-hero__lead">社内向けページです。人事担当より共有された登録コードをご用意のうえご登録ください。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">

			<?php if ( 'success' === $cobalt_staff_register_status ) : ?>
				<p class="form-message form-message--success" role="status">
					登録が完了しました。<a href="<?php echo esc_url( cobalt_logistics_page_url( 'staff-login' ) ); ?>">ログインページへ進む</a>
				</p>
			<?php elseif ( 'error' === $cobalt_staff_register_status ) : ?>
				<p class="form-message form-message--error" role="alert">
					<?php if ( $cobalt_staff_register_error_messages ) : ?>
						<?php echo esc_html( implode( '、', $cobalt_staff_register_error_messages ) ); ?>
					<?php else : ?>
						登録に失敗しました。入力内容をご確認のうえ再度お試しください。
					<?php endif; ?>
				</p>
			<?php endif; ?>

			<?php if ( 'success' !== $cobalt_staff_register_status ) : ?>
				<form class="contact-form" id="staff-register-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
					<input type="hidden" name="action" value="cobalt_staff_register">
					<?php wp_nonce_field( 'cobalt_staff_register', 'cobalt_staff_register_nonce' ); ?>

					<div class="contact-form__field">
						<label for="staff-name">氏名 <span class="required">必須</span></label>
						<input type="text" id="staff-name" name="staff_name" autocomplete="name" required>
					</div>
					<div class="contact-form__field">
						<label for="staff-id">社員ID（半角英数字） <span class="required">必須</span></label>
						<input type="text" id="staff-id" name="staff_id" autocomplete="username" pattern="[A-Za-z0-9]+" required>
					</div>
					<div class="contact-form__field">
						<label for="staff-email">メールアドレス <span class="required">必須</span></label>
						<input type="email" id="staff-email" name="staff_email" autocomplete="email" required>
					</div>
					<div class="contact-form__field">
						<label for="staff-password">パスワード（8文字以上） <span class="required">必須</span></label>
						<input type="password" id="staff-password" name="staff_password" autocomplete="new-password" minlength="8" required>
					</div>
					<div class="contact-form__field">
						<label for="staff-password-confirm">パスワード（確認用） <span class="required">必須</span></label>
						<input type="password" id="staff-password-confirm" name="staff_password_confirm" autocomplete="new-password" minlength="8" required>
					</div>
					<div class="contact-form__field">
						<label for="staff-reg-code">登録コード <span class="required">必須</span></label>
						<input type="text" id="staff-reg-code" name="staff_reg_code" autocomplete="off" required>
					</div>

					<button type="submit" class="btn btn--solid" style="width:100%;">登録する</button>
				</form>
			<?php endif; ?>

			<p style="text-align:center; margin-top: 24px;"><a href="<?php echo esc_url( cobalt_logistics_page_url( 'staff-login' ) ); ?>">既にアカウントをお持ちの方はこちら（ログイン）</a></p>
		</div>
	</section>

</main>

<?php
get_footer();
