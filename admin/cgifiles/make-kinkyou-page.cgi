#!/bin/bash

# 近況ページに表示する最大投稿件数
post_COUNT=10

# 初期化 ------------------------------
set -u
umask 0022
PATH='/usr/bin:/bin'
IFS=$(printf ' \t\n_'); IFS=${IFS%_}
export IFS LC_ALL=C LANG=C PATH

Tmp=/tmp/${0##*/}.$$
# -------------------------------------

# POST 内容読み取り
head -c ${CONTENT_LENGTH:-0} |
tr -d '\r'                   > $Tmp-cgivars

# 記事メタデータを設定
newest_post_postdate=$(date '+%Y-%m-%d %H:%M:%S')
post=$(./get-cgi-post-data post $Tmp-cgivars)

# ヘッダ出力
echo "Content-Type: text/plain; charset=UTF-8"
echo ""

# 最新の投稿をリストに反映（先頭行に追記）
echo -ne "${newest_post_postdate}\t${post}\n" > $Tmp-post-list
cat ../post-list.txt >> $Tmp-post-list

# 出力処理
head -n ${post_COUNT} $Tmp-post-list > $Tmp-post-newest-${post_COUNT}

awk -f make-kinkyou.awk $Tmp-post-newest-${post_COUNT} > $Tmp-kinkyou-page-parts
cat ../templates/kinkyou-template.html  |
sed "/@posts/r $Tmp-kinkyou-page-parts" |
sed "/@posts/d"                         > $Tmp-new-kinkyou-page


# awk -f make-kinkyou.awk \
#     -v posts_list_file="$Tmp-posts-newest-${post_COUNT}" \
#     ../templates/kinkyou-template.html       > $Tmp-new-kinkyou-page

# ファイルを置き換え
mv ../post-list.txt ../post-list-prev.txt
cat $Tmp-post-list > ../post-list.txt
mv ../../kinkyou.html ../../kinkyou-prev.html
mv $Tmp-new-kinkyou-page ../../kinkyou.html

echo "Kinkyou Updated."

# 終了処理 ----------------------------
rm -f $Tmp-*
# -------------------------------------
