<?php defined('IN_IA') or exit('Access Denied');?>			</div>
		</div>
	</div>
	<script>
		function subscribe(){
			$.post("<?php  echo url('utility/subscribe');?>", function(){
				setTimeout(subscribe, 5000);
			});
		}
		function sync() {
			$.post("<?php  echo url('utility/sync');?>", function(){
				setTimeout(sync, 60000);
			});
		}
		$(function(){
			subscribe();
			sync();
		});
		<?php  if($_W['uid']) { ?>
			function checknotice() {
				$.post("<?php  echo url('utility/notice')?>", {}, function(data){
					var data = $.parseJSON(data);
					$('#notice-container').html(data.notices);
					$('#notice-total').html(data.total);
					if(data.total > 0) {
						$('#notice-total').css('background', '#ff9900');
					} else {
						$('#notice-total').css('background', '');
					}
					setTimeout(checknotice, 60000);
				});
			}
			checknotice();
		<?php  } ?>

		<?php  if(defined('IN_MODULE')) { ?>
		$.getJSON("<?php  echo url('utility/checkupgrade/module', array('m' => IN_MODULE));?>", function(result) {
			if (result.message.errno == -10) {
				$('body').prepend('<div id="upgrade-tips-module" class="upgrade-tips"><a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODAwMzEzOV8xNzEwOTZfNDAwMDgyODUwMl8yXw" target="_blank">' + result.message.message + '</a></div>');
				if ($('#upgrade-tips').size()) {
					$('#upgrade-tips-module').css('top', '25px');
				}
			}
		});
		<?php  } ?>
		<?php  echo site_profile_perfect_tips();?>
	</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-base', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-base', TEMPLATE_INCLUDEPATH));?>
