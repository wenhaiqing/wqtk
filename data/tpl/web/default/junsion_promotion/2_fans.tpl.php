<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('staff');?>">员工管理</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('fans');?>">粉丝管理</a></li>
	<li><a href="<?php  echo $this->createWebUrl('stat');?>">数据统计</a></li>
</ul>
<style>
        th{
        	text-align: center !important;
        }
        td{
        	text-align: center !important;
        	white-space: normal !important;
			word-break: break-all !important;
        }
    </style>
<div class="main">
        <div style="padding-top: 15px;"></div>
        <div class="panel panel-default">
            <div class="table-responsive panel-body">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th>粉丝</th>
                        <th>账户资料地区</th>
                        <th>推荐人</th>
                        <?php  if(is_array($cfg['cols'])) { foreach($cfg['cols'] as $c) { ?>
                        <th><?php  echo $c['col_name'];?></th>
                        <?php  } } ?>
                        <th>关注时间</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>		
                        <td><img width="50" style="border-radius: 3px;" src="<?php  echo $item['avatar'];?>"/></br><?php  echo $item['nickname'];?></td>
                        <td><?php  echo $item['p'];?><br><?php  echo $item['c'];?></td>
                        <td>
                            <?php  echo $item['parent'];?>
                        </td>
                        <?php  $cols = unserialize($item['cols'])?>
                        <?php  if(is_array($cfg['cols'])) { foreach($cfg['cols'] as $c) { ?>
                        <td><?php  echo $cols[$c['col_name']];?></td>
                        <?php  } } ?>
                        <td>
                            <?php  if($item['follow']) { ?><?php  echo date('Y-m-d H:i:s',$item['followtime'])?><?php  } else { ?>取消关注<?php  } ?>
                        </td>
                    </tr>
                    <?php  } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php  echo $pager;?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
