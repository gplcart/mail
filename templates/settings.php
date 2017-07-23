<?php
/**
 * @package Mail
 * @author Iurii Makukh
 * @copyright Copyright (c) 2017, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
?>
<form method="post" class="form-horizontal">
  <input type="hidden" name="token" value="<?php echo $_token; ?>">
  <div class="form-group">
    <label class="col-md-2 control-label"><?php echo $this->text('Status'); ?></label>
    <div class="col-md-10">
      <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-default<?php echo empty($settings['status']) ? '' : ' active'; ?>">
          <input name="settings[status]" type="radio" autocomplete="off" value="1"<?php echo empty($settings['status']) ? '' : ' checked'; ?>>
          <?php echo $this->text('Enabled'); ?>
        </label>
        <label class="btn btn-default<?php echo empty($settings['status']) ? ' active' : ''; ?>">
          <input name="settings[status]" type="radio" autocomplete="off" value="0"<?php echo empty($settings['status']) ? ' checked' : ''; ?>>
          <?php echo $this->text('Disabled'); ?>
        </label>
      </div>
      <div class="help-block">
        <?php echo $this->text('If enabled the module will catch all outcoming emails and send them using own handlers'); ?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-2 control-label"><?php echo $this->text('SMTP authentication'); ?></label>
    <div class="col-md-10">
      <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-default<?php echo empty($settings['auth']) ? '' : ' active'; ?>">
          <input name="settings[auth]" type="radio" autocomplete="off" value="1"<?php echo empty($settings['auth']) ? '' : ' checked'; ?>>
          <?php echo $this->text('Enabled'); ?>
        </label>
        <label class="btn btn-default<?php echo empty($settings['auth']) ? ' active' : ''; ?>">
          <input name="settings[auth]" type="radio" autocomplete="off" value="0"<?php echo empty($settings['auth']) ? ' checked' : ''; ?>>
          <?php echo $this->text('Disabled'); ?>
        </label>
      </div>
      <div class="help-block">
        <?php echo $this->text('Log in using an authentication mechanism supported by the SMTP server'); ?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-2 control-label"><?php echo $this->text('SMTP encryption'); ?></label>
    <div class="col-md-4">
      <select  name="settings[secure]" class="form-control">
        <option value="tls"<?php echo ($settings['secure'] == 'tls') ? ' selected' : ''; ?>>
          <?php echo $this->text('TLS'); ?>
        </option>
        <option value="ssl"<?php echo ($settings['secure'] == 'ssl') ? ' selected' : ''; ?>>
          <?php echo $this->text('SSL'); ?>
        </option>
      </select>
      <div class="help-block">
        <?php echo $this->text('Select a authentication protocol for the SMTP server'); ?>
      </div>
    </div>
  </div>
  <div class="form-group required<?php echo $this->error('host', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('SMTP hosts'); ?></label>
    <div class="col-md-4">
      <textarea name="settings[host]" class="form-control"><?php echo $this->e($settings['host']); ?></textarea>
      <div class="help-block">
        <?php echo $this->error('host'); ?>
        <div class="text-muted">
          <?php echo $this->text('Enter a list of SMTP hosts, one per line. The very first host will be main, other - backup'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group required<?php echo $this->error('user', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('SMTP user'); ?></label>
    <div class="col-md-4">
      <input name="settings[user]" class="form-control" value="<?php echo $this->e($settings['user']); ?>">
      <div class="help-block">
        <?php echo $this->error('user'); ?>
        <div class="text-muted">
          <?php echo $this->text('A username to be used for authentication on the SMTP server'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group required<?php echo $this->error('password', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('SMTP password'); ?></label>
    <div class="col-md-4">
      <input name="settings[password]" type="password" class="form-control" autocomplete="new-password" value="<?php echo $this->e($settings['password']); ?>">
      <div class="help-block">
        <?php echo $this->error('password'); ?>
        <div class="text-muted">
          <?php echo $this->text('A password to be used for authentication on the SMTP server'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group required<?php echo $this->error('port', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('SMTP port'); ?></label>
    <div class="col-md-4">
      <input name="settings[port]" class="form-control" value="<?php echo $this->e($settings['port']); ?>">
      <div class="help-block">
        <?php echo $this->error('port'); ?>
        <div class="text-muted">
          <?php echo $this->text('Enter a numeric SMTP port. SMTP by default uses for submissions port 587, SMTPS (secured) uses 465'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-4 col-md-offset-2">
      <div class="btn-toolbar">
        <a href="<?php echo $this->url("admin/module/list"); ?>" class="btn btn-default"><?php echo $this->text("Cancel"); ?></a>
        <button class="btn btn-default save" name="save" value="1"><?php echo $this->text("Save"); ?></button>
      </div>
    </div>
  </div>
</form>