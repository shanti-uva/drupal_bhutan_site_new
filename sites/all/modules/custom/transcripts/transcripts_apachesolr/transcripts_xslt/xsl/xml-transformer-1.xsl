<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="xml" encoding="UTF-8" indent="yes"/>

  <xsl:param name="tier_id" select="'content_bod'"/>

  <xsl:template match="/">
    <xsl:apply-templates select="TITLE"/>
    <!-- QuillDriver -->
    <xsl:apply-templates select="transcript"/>
    <!-- InqScribe -->
  </xsl:template>

  <xsl:template match="TITLE">
    <tcus>
      <xsl:apply-templates select="TEXT/S"/>
    </tcus>
  </xsl:template>

  <!-- Here's what a transcript sentence looks like:
        <S who="N400005" id="d148e29">
           <FORM xml:lang="bo">དེ་རིང་གནམ་ཡག་པོ་ར་ཅིག་༿འདྲ་ཅིག༾མི་འདུག་གས།</FORM>
           <FORM xml:lang="bo-Latn">de ring gnam yag po ra cig {'dra cig}mi 'dug gas/</FORM>
           <TRANSL xml:lang="en">Isn't it a nice day today?</TRANSL>
           <TRANSL xml:lang="zh">今天的天气多好啊, 是吧!</TRANSL>
           <AUDIO end="8.925999997392298" start="7.63"/>
        </S>
  -->
  <xsl:template match="S">
    <xsl:variable name="sid" select="@id"/>
    <tcu>
      <xsl:if test="string(AUDIO/@start)">
        <start>
          <xsl:value-of select="format-number(AUDIO/@start,'0.000')"/>
        </start>
        <xsl:if test="string(AUDIO/@end)">
          <end>
            <xsl:value-of select="format-number(AUDIO/@end,'0.000')"/>
          </end>
        </xsl:if>
      </xsl:if>
      <xsl:variable name="speaker">
        <xsl:value-of select="substring(//SPEAKER[@personId=current()/@who]/name_bod,1,255)"/>
      </xsl:variable>
      <xsl:if test="normalize-space($speaker)">
        <speakers>
          <ss_speaker_bod>
            <xsl:value-of select="$speaker"/>
          </ss_speaker_bod>
        </speakers>
      </xsl:if>
      <tiers>
        <xsl:if test="normalize-space(FORM[@xml:lang='bo'])">
          <content_bod>
            <xsl:value-of select="normalize-space(FORM[@xml:lang='bo'])"/>
          </content_bod>
        </xsl:if>
        <xsl:if test="normalize-space(FORM[@xml:lang='bo-Latn'])">
          <ts_content_wylie>
            <xsl:value-of select="normalize-space(FORM[@xml:lang='bo-Latn'])"/>
          </ts_content_wylie>
        </xsl:if>
        <xsl:if test="normalize-space(TRANSL[@xml:lang='en'])">
          <ts_content_eng>
            <xsl:value-of select="normalize-space(TRANSL[@xml:lang='en'])"/>
          </ts_content_eng>
        </xsl:if>
        <xsl:if test="normalize-space(TRANSL[@xml:lang='zh'])">
          <ts_content_zho>
            <xsl:value-of select="normalize-space(TRANSL[@xml:lang='zh'])"/>
          </ts_content_zho>
        </xsl:if>
      </tiers>
    </tcu>
  </xsl:template>

  <!-- here's what an inqscribe sentence looks like:
      <scene id="1" in="00:00:00.00" out="00:00:03.06">:མཆོད་མཆོད་མཆོད་ཕ་ལྷ་མ་ལྷ་མཆོད།།</scene>
  -->
  <xsl:template match="transcript">
    <xsl:variable name="alphabet">
      <xsl:choose>
        <xsl:when test="contains($tier_id, 'dzo_bod')">dzo</xsl:when>
        <xsl:otherwise>bod</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <tcus>
      <xsl:for-each select="scene">
        <xsl:variable name="sid" select="concat('s',format-number(@id,'000'))"/>
        <xsl:variable name="begintime">
          <xsl:call-template name="convert-time">
            <xsl:with-param name="time" select="@in"/>
          </xsl:call-template>
        </xsl:variable>
        <xsl:variable name="endtime">
          <xsl:call-template name="convert-time">
            <xsl:with-param name="time" select="@out"/>
          </xsl:call-template>
        </xsl:variable>
        <tcu>
          <start>
            <xsl:value-of select="normalize-space($begintime)"/>
          </start>
          <end>
            <xsl:value-of select="normalize-space($endtime)"/>
          </end>
          <xsl:if test="normalize-space(@speaker)">
            <xsl:variable name="speaker" select="normalize-space(@speaker)"/>
            <xsl:choose>
              <xsl:when test="contains($speaker, '/')"> <!-- two speaker name forms -->
                <speakers>
                  <xsl:variable name="bod"
                                select="normalize-space(substring-before($speaker,'/'))"/>
                  <xsl:if test="$bod">
                    <xsl:choose>
                      <xsl:when test="$alphabet = 'bod'">
                        <ss_speaker_bod>
                          <xsl:value-of select="$bod"/>
                        </ss_speaker_bod>
                      </xsl:when>
                      <xsl:when test="$alphabet = 'dzo'">
                        <ss_speaker_dzo>
                          <xsl:value-of select="$bod"/>
                        </ss_speaker_dzo>
                      </xsl:when>
                    </xsl:choose>
                  </xsl:if>
                  <xsl:variable name="eng" select="normalize-space(substring-after($speaker,'/'))"/>
                  <xsl:if test="$eng">
                    <ss_speaker_phon>
                      <xsl:value-of select="$eng"/>
                    </ss_speaker_phon>
                  </xsl:if>
                </speakers>
              </xsl:when>
              <xsl:otherwise> <!-- only one speaker name form -->
                <speakers>
                  <xsl:variable name="bod" select="$speaker"/>
                  <xsl:if test="$bod">
                    <xsl:choose>
                      <xsl:when test="$alphabet = 'bod'">
                        <ss_speaker_bod>
                          <xsl:value-of select="$bod"/>
                        </ss_speaker_bod>
                      </xsl:when>
                      <xsl:when test="$alphabet = 'dzo'">
                        <ss_speaker_dzo>
                          <xsl:value-of select="$bod"/>
                        </ss_speaker_dzo>
                      </xsl:when>
                    </xsl:choose>
                  </xsl:if>
                </speakers>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:if>
          <tiers>
            <xsl:choose>
              <xsl:when test="contains(.,'/')"> <!-- tiers are in two different languages -->
                <xsl:variable name="bod" select="normalize-space(substring-before(.,'/'))"/>
                <xsl:if test="$bod">
                  <xsl:choose>
                    <xsl:when test="$alphabet = 'bod'">
                      <content_bod>
                        <xsl:value-of select="$bod"/>
                      </content_bod>
                    </xsl:when>
                    <xsl:when test="$alphabet = 'dzo'">
                      <dzo_bod>
                        <xsl:value-of select="$bod"/>
                      </dzo_bod>
                    </xsl:when>
                  </xsl:choose>
                </xsl:if>
                <xsl:variable name="eng" select="normalize-space(substring-after(.,'/'))"/>
                <xsl:if test="$eng">
                  <ts_content_eng>
                    <xsl:value-of select="$eng"/>
                  </ts_content_eng>
                </xsl:if>
              </xsl:when>
              <xsl:otherwise> <!-- only one tier language -->
                <xsl:variable name="bod" select="normalize-space(.)"/>
                <xsl:if test="$bod">
                  <xsl:choose>
                    <xsl:when test="$alphabet = 'bod'">
                      <content_bod>
                        <xsl:value-of select="$bod"/>
                      </content_bod>
                    </xsl:when>
                    <xsl:when test="$alphabet = 'dzo'">
                      <dzo_bod>
                        <xsl:value-of select="$bod"/>
                      </dzo_bod>
                    </xsl:when>
                  </xsl:choose>
                </xsl:if>
              </xsl:otherwise>
            </xsl:choose>
          </tiers>
        </tcu>
      </xsl:for-each>
    </tcus>
  </xsl:template>

  <xsl:template name="convert-time">
    <xsl:param name="time" select="'0'"/>

    <xsl:choose>
      <xsl:when test="string-length(substring-after($time, '.')) = 2"> <!-- frames (30fps) -->
        <xsl:variable name="h" select="number(substring($time,1,2))"/>
        <xsl:variable name="m" select="number(substring($time,4,2))"/>
        <xsl:variable name="s" select="number(substring($time,7,2))"/>
        <xsl:variable name="f" select="number(substring($time,10,2))"/>
        <xsl:value-of select="format-number($h*3600 + $m*60 + $s + $f div 30,'0.000')"/>
      </xsl:when>
      <xsl:when test="string-length(substring-after($time, '.')) = 3"> <!-- milliseconds -->
        <xsl:variable name="h" select="number(substring($time,1,2))"/>
        <xsl:variable name="m" select="number(substring($time,4,2))"/>
        <xsl:variable name="s" select="number(substring($time,7,2))"/>
        <xsl:variable name="x" select="number(substring($time,10,3))"/>
        <xsl:value-of select="format-number($h*3600 + $m*60 + $s + $x div 1000,'0.000')"/>
      </xsl:when>
    </xsl:choose>

  </xsl:template>

</xsl:stylesheet>
